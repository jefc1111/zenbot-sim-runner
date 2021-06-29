<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\Request;

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
}