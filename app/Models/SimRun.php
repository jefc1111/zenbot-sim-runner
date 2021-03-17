<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StrategyOption;
use App\Models\Strategy;
use App\Models\SimRunBatch;

class SimRun extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public array $unsaved_strategy_option_data = []; // key is option id, value is string value

    public function strategy_options()
    {
        return $this->belongsToMany(StrategyOption::class)->withPivot('value');
    }

/*
CUNT
NEXT:
In sim run batch controller `store` method, we need to get the inputted data from the 
init form (by passing it along through the session..?). 
Also looks like I missed buy_pct and sell_pct in the form. 
*/

    public function strategy()
    {
        return $this->belongsTo(Strategy::class);
    }

    public function sim_run_batch()
    {
        return $this->belongsTo(SimRunBatch::class);
    }

    public function set_unsaved_strategy_option_data(array $strategy_option_data): void
    {
        $this->unsaved_strategy_option_data = $strategy_option_data;
    }

    public function get_value_for_option(StrategyOption $strategy_option): string
    {
        return array_key_exists($strategy_option->id, $this->unsaved_strategy_option_data) 
        ? $this->unsaved_strategy_option_data[$strategy_option->id]
        : $strategy_option->default;
    }

    public function cmd(): string
    {
        return "zenbot sim {$this->sim_run_batch->get_selector()} 
        --strategy {$this->strategy->name} 
        --start {$this->sim_run_batch->start->format('Y-m-d')} 
        --end {$this->sim_run_batch->end->format('Y-m-d')}
        --buy_pct {$this->sim_run_batch->buy_pct}
        --sell_pct {$this->sim_run_batch->sell_pct}
        " .$this->option_str();
    }

    private function option_str(): string
    {
        // Including any value for `period_length` was causing a `Error: invalid bucket size spec:` error 
        // at cmd line
        // I _think_ `period_length` is just a dupe of `period` anyway. Maybe. 
        return $this->strategy_options
        ->filter(fn($o) => $o->name !== 'period_length')
        ->map(fn($o) => "{$o->name}=\"{$o->pivot->value}\"")
        ->join(' --');
    }
}
