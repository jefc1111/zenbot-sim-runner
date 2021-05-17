<?php

namespace App\Models;

use App\Models\SimRunBatch;
use App\Models\StrategyOption;

class NextBatchRecommendation {
    public $min;
    public $max;
    public $step;

    function __construct(
        private SimRunBatch $batch,
        private StrategyOption $opt
    )
    {
        // This intiliasises some stuff. @todo: refactor so this is not necessary
        $this->batch->trend_score_for_option($this->opt);        

        $next_value_up = $this->batch->option_values($this->opt)->max() 
            + (float) $this->batch->first_step_interval_for_option($this->opt);

        $first_step = $this->batch->first_step_interval_for_option($this->opt);

        $effect_on_trend = $this->opt->effect_on_trend();

        $this->min = $this->calculate_min($effect_on_trend, $this->batch, $this->opt, $next_value_up, $first_step);
        $this->max = $this->calculate_max($effect_on_trend, $this->batch, $this->opt, $next_value_up, $first_step);
        $this->step = $this->calculate_step($effect_on_trend, $first_step);
    }

    private function calculate_min(int $effect_on_trend, SimRunBatch $batch, StrategyOption $opt, $next_value_up, $step)
    {
        return match($effect_on_trend) {
            1 => $next_value_up,
            -1 => - $step - $batch->option_values($opt)->max(),            
            default => $batch->option_values($opt)->last()
        };
    }

    private function calculate_max(int $effect_on_trend, SimRunBatch $batch, StrategyOption $opt, $next_value_up, $step)
    {
        return match($effect_on_trend) {
            1 => $next_value_up + $batch->option_values($opt)->max() - $batch->option_values($opt)->min(),
            -1 => $batch->option_values($opt)->min() - $step,            
            default => $batch->option_values($opt)->last()
        };
    }

    private function calculate_step(int $effect_on_trend, $step)
    {
        return match($effect_on_trend) {
            1, -1 => $step,                        
            default => 0
        };
    }
}