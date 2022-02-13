<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use SensioLabs\AnsiConverter\Theme\Theme;

trait InvokesZenbot {
    private function cmd_primary_components(): array
    {
        return [
            config('zenbot.node_executable'), 
            '--max-old-space-size='.config('zenbot.node_max_old_space_size'),
            config('zenbot.location').'/zenbot.js'
        ]; 
    }

    private function cmd_date_components(\App\Models\SimRunBatch $sim_run_batch, bool $as_epoch = false): array
    {
        $start_str = $as_epoch 
        ? $sim_run_batch->start->timestamp."000"  
        : $sim_run_batch->start->format('Y-m-d'); 

        $end_str = $as_epoch 
        ? $sim_run_batch->end->timestamp."000"
        : $sim_run_batch->end->format('Y-m-d');
        
        return [
            "--start={$start_str}", 
            "--end={$end_str}"
        ];
    }

    private function write_log_file_and_get_last_msg(Process $process, string $path): string
    {
        $full_path = Storage::disk('zenbot-logs')->path($path);

        foreach ($process as $type => $data) {
            //Storage::disk('zenbot-logs')->append($path, $data.PHP_EOL, null);

            file_put_contents($full_path, $data.PHP_EOL, FILE_APPEND);
        }

        return $data;
    }

    private function tail_log_file($path): string
    {
        if (! \Storage::disk('zenbot-logs')->exists($path)) {
            return [];
        }

        // http://www.geekality.net/2011/05/28/php-tail-tackling-large-files/
        function tail($filename, $lines = 10, $buffer = 4096) {
            // Open the file
            $f = fopen($filename, "rb");

            // Jump to last character
            fseek($f, -1, SEEK_END);

            // Read it and adjust line number if necessary
            // (Otherwise the result would be wrong if file doesn't end with a blank line)
            if(fread($f, 1) != "\n") $lines -= 1;

            // Start reading
            $output = '';
            $chunk = '';

            // While we would like more
            while(ftell($f) > 0 && $lines >= 0) {
                // Figure out how far back we should jump
                $seek = min(ftell($f), $buffer);

                // Do the jump (backwards, relative to where we are)
                fseek($f, -$seek, SEEK_CUR);

                // Read a chunk and prepend it to our output
                $output = ($chunk = fread($f, $seek)).$output;

                // Jump back to where we started reading
                fseek($f, -mb_strlen($chunk, '8bit'), SEEK_CUR);

                // Decrease our line counter
                $lines -= substr_count($chunk, "\n");
            }

            // While we have too many lines
            // (Because of buffer size we might have read too many)
            while ($lines++ < 0) {
                // Find first newline and remove all text before that
                $output = substr($output, strpos($output, "\n") + 1);
            }

            // Close file and return
            fclose($f);

            return $output;
        }

        $theme = new Class() extends Theme {                        
            public function asArray() {
                return array(
                    'black' => 'black',
                    'red' => 'red',
                    'green' => 'green',
                    'yellow' => 'yellow',
                    'blue' => 'blue',
                    'magenta' => 'darkmagenta',
                    'cyan' => 'cyan',
                    'white' => 'white',

                    'brblack' => 'black',
                    'brred' => 'red',
                    'brgreen' => 'lightgreen',
                    'bryellow' => 'lightyellow',
                    'brblue' => 'lightblue',
                    'brmagenta' => 'magenta',
                    'brcyan' => 'lightcyan',
                    'brwhite' => 'white',
                );
            }
        };
        
        $converter = new AnsiToHtmlConverter($theme);

        $tail_output = tail(\Storage::disk('zenbot-logs')->path($path), 100);

        return $converter->convert($tail_output);
    }
}