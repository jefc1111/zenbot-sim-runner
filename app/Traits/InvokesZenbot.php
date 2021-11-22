<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

trait InvokesZenbot {
    private function cmd_primary_components(): array
    {
        return [
            config('zenbot.node_executable'), 
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
        $last_msg = '';
        \Log::error(Storage::disk('zenbot-logs')->path('/'));
        \Log::error($path);

        Storage::disk('zenbot-logs')->put($path, '');
        
        foreach ($process as $type => $data) {
            Storage::disk('zenbot-logs')->append($path, $data);

            $last_msg = $data;
        }

        return $last_msg;
    }
}