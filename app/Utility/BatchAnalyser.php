<?php

use App\Models\SimRunBatch;
use App\Models\SimRun;
use App\Models\Strategy;

class BatchAnalyser
{
    private Strategy $winning_strategy;
    private Strategy $winning_sim_run;

    function __construct(
        private SimRumBatch $batch
    ) 
    {
        $this->winning_sim_run = $this->sim_runs
        ->filter(fn($sr) => $sr->result('vs_buy_hold') == $this->best_vs_buy_hold())
        ->first();

        $this->winning_strategy = $this->winning_sim_run->strategy;

        $this->do_analysis();
    }

    private function do_analysis(): void
    {

    }


}
