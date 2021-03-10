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
        $selector = $this->sim_run_batch->exchange->name.".".$this->sim_run_batch->product->name;

        return "zenbot sim $selector --strategy {$this->strategy->name} --days {$this->sim_run_batch->days} " . 
        $this->strategy_options->map(fn($o) => "{$o->name}=\"{$o->pivot->value}\"")->join(' --');
    }
}
