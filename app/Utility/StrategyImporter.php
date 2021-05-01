<?php 

namespace App\Utility;

use App\Models\Strategy;
use App\Models\StrategyOption;
use Symfony\Component\Process\Process;

class StrategyImporter 
{
    private $zenbot_location = '';
    
    function __construct(string $zenbot_location) 
    {
        // Check location exists etc
        $this->zenbot_location = $zenbot_location;
    }
    
    public function run() 
    {
        // Get strategy list from Zenbot
        $process = new Process([
            config('zenbot.node_executable'),
            config('zenbot.location').'/zenbot.js',
            'list-strategies'
        ]);

        $process->setTimeout(60);
        $process->setWorkingDirectory(config('zenbot.location'));
        $process->run();

        // Discard ANSI color control characters in output
        $output = preg_replace('/\e[[][A-Za-z0-9];?[0-9]*m?/', '', $process->getOutput());

        // Split output into lines array
        $lines = [];
        if ($process->isSuccessful()) {
            $lines = explode("\n", $output);
        }

        $strategy = null;
        foreach ($lines as $i => $line) {
            if (
                strlen($line) > 1 &&                      // Line has more than one character
                substr($line, 0, 1) !== ' '  // First character is not a space
            ) {
                // Every strategy should have 'description' or 'options' on the next line
                $nextline = trim($lines[$i + 1]);
                if (
                    substr_count($nextline, 'description') !== 1 &&
                    substr_count($nextline, 'options') !== 1
                ) {
                    // Not actually a new strategy
                    continue;
                }

                // We are starting a new strategy listing
                $strategy = new Strategy([
                    // Only use first word on this line
                    'name' => explode(' ', $line, 2)[0]
                ]);
                $strategy->save();
            }
            else if (substr_count($line, 'description') === 1) {
                $strategy->description = trim($lines[$i + 1]);
                $strategy->save();
            }
            else if (substr_count($line, '--') > 0) {
                $strategy->options()->save(new StrategyOption([
                    'name' => $this->extract_option_name(trim($line)),
                    'description' => $this->extract_option_description(trim($line)),
                    'default' => $this->extract_option_default_value(trim($line)),
                    'unit' => $this->extract_option_unit(trim($line)),
                    'step' => $this->get_step_size(
                        $this->extract_option_default_value(trim($line)), 
                        $this->extract_option_unit(trim($line))
                    )
                ]));
            }
        }
    }

    private function extract_option_name(string $line): string 
    {
        return str_replace(
            '--', 
            '', 
            substr($line, 0, strpos($line, '='))
        );
    }

    private function extract_option_description(string $line): string 
    {
        $length = $this->line_has_default($line)
            ? strpos($line, '(default') - strpos($line, '  ')
            : strlen($line);

        return trim(
            substr(
                $line, 
                strpos($line, '  '), 
                $length
            )
        );
    }

    private function has_letters_and_numbers(string $str): bool 
    {
        return preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $str);
    }

    private function extract_option_default_value(string $line): string
    {
        $full_option_default = $this->extract_option_default($line); // i.e '1h', '30m', '100', 'c', etc etc

        return $this->has_letters_and_numbers($full_option_default) 
            ? preg_replace("/[^0-9.]/", "", $full_option_default) // It was something like '30m' so we just want the '30'
            : $full_option_default; // It was either just a number; '14' or just letters; 'gdax'. Either way, we want the whole string
    }

    private function extract_option_unit(string $line): string
    {
        $full_option_default = $this->extract_option_default($line);

        return $this->has_letters_and_numbers($full_option_default) 
            ? preg_replace('/[0-9]+/', '', $this->extract_option_default($line))
            : ''; // No unit to extract!
    }

    private function extract_option_default(string $line): string
    {
        if (! $this->line_has_default($line)) {
            return '';
        }
        
        $full_default_string = substr(
            $line, 
            strpos($line, '(default: '), 
            strlen($line)
        );

        // Now get rid of everything except the actual default value
        return str_replace(
            ')', 
            '', 
            str_replace(
                '(default: ', 
                '', 
                $full_default_string
            )
        );
    }

    private function get_step_size(string $option_default, string $option_unit): string
    {
        if ($option_unit === 'h') {
            return 15; // Somehow need to also change the saved unit to m from h...
        }

        if ($option_unit === 'm') {
            return strlen($option_default) > 2 ? 10 : 1;
        }

        if ($option_unit === 's') {
            return 5;
        }

        if (is_numeric($option_default) && substr_count($option_default, '.') === 1) {
            return strlen(explode('.', $option_default)[1]) > 2 ? 0.01 : 0.1;
        }

        if (is_numeric($option_default)) {
            return strlen($option_default) > 2 ? 10 : 1;
        }

        // Has it got a decimal point? I.e. if is 1.6 default, step size be 0.1
        // Is it a 'known' unit? I.e. if minutes, maybe step size be 15 minutes?
        // Maybe qty digits is relevant? So step sixe for 1000 might be 100?

        // This can be overridden by user anytime anyway...
        return '';
    }

    private function line_has_default(string $line): bool
    {
        return substr_count($line, '(default') === 1;
    }

    // Make A StrategiesDocLine class
    // Make A StrategiesDocOptionLine class
}