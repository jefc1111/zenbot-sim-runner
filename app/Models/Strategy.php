<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StrategyOption;

class Strategy extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function options()
    {
        return $this->hasMany(StrategyOption::class);
    }

    public function sim_runs()
    {
        return $this->hasMany(SimRun::class);
    }

    public function get_varying_options_for_sim_runs($sim_runs)
    {
        return $this->options->filter(function($opt) use($sim_runs) {
            $all_values_for_opt = $sim_runs->map(fn($sr) => $sr->strategy_options->find($opt->id)?->pivot->value)->values();
        
            // We only want to return strategy options where the set of sim runs given has more than 
            // one distinct value (i.e. the user did select a range for interpolation)
            return count($all_values_for_opt->unique()) > 1;
        });
    }

    public function option_values(StrategyOption $opt)
    {
        return $this->sim_runs->filter(fn($sr) => $sr->status === 'complete')
            ->sortBy(fn($sr) => $sr->result('profit'))
            ->map(fn($sr) => (float) $sr->strategy_options->find($opt->id)?->pivot->value)
            ->values();
    }
}
