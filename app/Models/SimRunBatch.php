<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Strategy;
use App\Models\StrategyOption;
use App\Models\SimRun;
use App\Models\Exchange;
use App\Models\Product;
use App\Models\NextBatchRecommendation;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Throwable;
use App\Jobs\ProcessSimRun;
use Illuminate\Database\Eloquent\Collection;

class SimRunBatch extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $dates = [
        'start',
        'end',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function sim_runs()
    {
        return $this->hasMany(SimRun::class);
    }

    public function exchange()
    {
        return $this->belongsTo(Exchange::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function parent_batch()
    {
        return $this->belongsTo('App\Models\SimRunBatch', 'parent_batch_id');
    }

    public function child_batch()
    {
        return $this->hasOne('App\Models\SimRunBatch', 'parent_batch_id');
    }

    public function spawn_child()
    {
        $faked_input_data = [];

        foreach ($this->get_varying_strategy_options() as $opt) {
            $rec = $this->get_recommendation_for_option($opt);
            
            $faked_input_data[$opt->id.'-min'] = $rec->min;
            $faked_input_data[$opt->id.'-max'] = $rec->max;
            $faked_input_data[$opt->id.'-step'] = $rec->step;
        }

        // We're only ever going to actually get one strategy here because as it stands,
        // auto-spawned batches are based only on the most succesful strategy from the seed
        // batch
        $strategies = $child_batch->make_sim_runs($faked_input_data);

        $child_batch = SimRunBatch::create(array_merge(
            $this->attributesToArray(),             
            [ 'parent_batch_id' => $this->id ]
        ));
        
        foreach ($strategies as $strategy) {
            foreach ($strategy->sim_runs as $sim_run) {                
                $prepped_data = [];

                foreach ($sim_run->unsaved_strategy_option_data as $opt_id => $value) {
                    $prepped_data[$opt_id] = ['value' => $value];
                }

                SimRun::create([
                    'strategy_id' => $strategy->id,
                    'sim_run_batch_id' => $child_batch->id
                ])->strategy_options()->sync($prepped_data);
            }
        }

        return $child_batch;
    }

    public function humanised_date_range(): string 
    {
        return substr($this->start, 0, 10)." to ".substr($this->end, 0, 10);
    } 

    public function qty_strategies()
    {
        return $this->get_all_strategies_used()->count();
    } 

    private function get_all_strategies_used()
    {
        return $this->sim_runs->map(fn($sr) => $sr->strategy)->unique();
    }

    public function get_pair_name(): string 
    {
        return $this->product->asset."-".$this->product->currency;
    }

    public function get_selector(): string
    {
        return $this->exchange->name.".".$this->get_pair_name();
    }

    public static function make_sim_runs(array $input_data)
    {        
        if (! function_exists('contains_only_nulls')) {
            function contains_only_nulls(array $arr): bool 
            {
                return empty(array_filter($arr, fn($i) => ! is_null($i)));
            }   
        }             

        $by_option_id = [];

        foreach ($input_data as $k => $v) {
            [ $option_id, $option_attribute ] = explode('-', $k);
            
            if (! array_key_exists($option_id, $by_option_id)) {
                $by_option_id[$option_id] = ['strategy_option_id' => $option_id];    
            }

            $by_option_id[$option_id][$option_attribute] = $v;
        }
        
        // Expand onmthe above to add the actual strategy option instance and push the other stuff
        // down into an 'input_data' attribute for each outer array entry
        $sim_run_data = array_map(function($item) {
            return [
                'strategy_option' => StrategyOption::findOrFail($item['strategy_option_id']),
                'input_data' => $item
            ];
        }, $by_option_id);

        // Now work out what strategies are involved
        $strategy_ids = array_values(
            array_unique(
                array_map(function($item) {
                    return $item['strategy_option']->strategy_id;
                }, $sim_run_data)
            )
        );

        $strategies = Strategy::findMany($strategy_ids);

        // Now feed each strategy any input data pertaining to that strategy
        // and where the user hasn't left everything NULL
        // It may be that there aren't any to feed the strategy. In that case,
        // there would only be one sim run generated, with all defaults
        //
        // So we're returning a list of strategies, but now each one comes with 
        // a list of sim_runs
        return $strategies->map(function($strategy) use($sim_run_data) {
            return self::make_sim_runs_for_strategy(
                $strategy,
                array_map(
                    function($item) {
                        return $item['input_data'];
                    }, 
                    array_filter(
                        $sim_run_data,
                        function($item) use ($strategy) {                        
                            return $item['strategy_option']->strategy_id == $strategy->id 
                                && ! contains_only_nulls(array_values(\Arr::except($item['input_data'], ['strategy_option_id'])));
                        }
                    )
                )
            );
        });
    }

    // This should be somewhere else probably, and not just randomly a static function LOL
    // https://gist.github.com/cecilemuller/4688876
    private static function get_combinations($arrays) {
        $result = array(array());
        foreach ($arrays as $property => $property_values) {
            $tmp = array();
            foreach ($result as $result_item) {
                foreach ($property_values as $property_value) {
                    $tmp[] = array_replace($result_item, array($property => $property_value));
                }
            }
            $result = $tmp;
        }
        return $result;
    }

    private static function make_sim_runs_for_strategy(Strategy $strategy, array $input_data): Strategy
    {
        $option_values_keyed_by_option_id = [];

        foreach ($input_data as $option_spec) {
            $option_values_keyed_by_option_id[$option_spec['strategy_option_id']] = [];

            $step = is_null($option_spec['step']) 
            ? $strategy->options->find($option_spec['strategy_option_id'])->step 
            : $option_spec['step'];

            $val = $option_spec['min'];

            // Doing it this way means if someone choose min: 5, max: 15, step: 3, you get
            // 5, 8, 11, 14, 15 (papering over the cracks of anyone's bad maths, hopefully)
            while ($val < $option_spec['max']) {
                $option_values_keyed_by_option_id[$option_spec['strategy_option_id']][] = strval($val);

                $val += $step;
            }

            $option_values_keyed_by_option_id[$option_spec['strategy_option_id']][] = $option_spec['max'];
        }

        // Get array of arrays which expresses all possible combinations of options
        $all_combinations = self::get_combinations($option_values_keyed_by_option_id);

        $strategy->sim_runs = array_map(function($combination) use($strategy) {
            $sim_run = new SimRun();
        
            $sim_run->strategy = $strategy;

            $sim_run->set_unsaved_strategy_option_data($combination);

            return $sim_run;
        }, $all_combinations);

        return $strategy;
    }    

    public function run()
    {
        $that = $this;

        // Maybe also want to check if best vs_buy_hold is an improvement on last time
        $auto_spawn_new_batch = env('AUTO_SPAWN_BATCHES', false);

        $batch = Bus::batch(
            $this->sim_runs->map(fn($sr) => new ProcessSimRun($sr))
        )->then(function(Batch $batch) {
            $success = true;
            // All jobs completed successfully...
        })->catch(function(Batch $batch, Throwable $e) {
            $success = false;
            // First batch job failure detected...
        })->finally(function(Batch $batch) use($that, $auto_spawn_new_batch) {
            // The batch has finished executing...           
            if ($auto_spawn_new_batch && ! $that->no_recommendation_possible()) {
                // Analyses the batch that just completed and attempts to create a new batch of sim runs with 
                // attributes that 'lead on' from those in the batch that just compoleted. 
                $new_batch = $that->spawn_child(); 

                $new_batch->run();
            }
        })->dispatch();
        
        return [
            'success' => true,
            'error' => 'error'
        ];
    }

    public function qty_complete(): int
    {
        return $this->sim_runs->filter(fn($sr) => $sr->result || $sr->log)->count();
    }

    public function qty_errored(): int
    {
        return $this->sim_runs->filter(fn($sr) => $sr->log)->count();
    }

    public function percent_complete(): int
    {
        return $this->sim_runs->isEmpty() ? 0 : ($this->qty_complete() / $this->sim_runs->count()) * 100;
    }

    public function best_vs_buy_hold()
    {
        return $this->sim_runs->map(fn($sr) => $sr->result('vs_buy_hold'))->max();
    }

    private function winning_sim_run(): SimRun
    {   
        return $this->sim_runs->filter(fn($sr) => $sr->result('vs_buy_hold') == $this->best_vs_buy_hold())->first();
    }

    public function winning_strategy(): Strategy
    {
        return $this->winning_sim_run()->strategy;
    }

    public function all_sim_runs_for_strategy(Strategy $strategy, string $sort_by_result_attr = null): Collection
    {
        $sim_runs = $this->sim_runs->where('strategy_id', $strategy->id);

        return $sort_by_result_attr ? $sim_runs->sortBy(fn($sr) => $sr->result('vs_buy_hold')) : $sim_runs;
    }

    // $sim_runs all need to have the same strategy so need to probably check that
    public function get_varying_strategy_options()
    {
        $winning_strategy = $this->winning_strategy();

        $runs_for_winning_strategy = $this->all_sim_runs_for_strategy($winning_strategy);

        return $winning_strategy->options->filter(function($opt) use($runs_for_winning_strategy) {
            $all_values_for_opt = $runs_for_winning_strategy->map(fn($sr) => $sr->strategy_options->find($opt->id)?->pivot->value)->values();
        
            // We only want to return strategy options where the set of sim runs given has more than 
            // one distinct value (i.e. the user did select a range for interpolation)
            return count($all_values_for_opt->unique()) > 1;
        });
    }

    public function option_values(StrategyOption $opt)
    {
        return $this->all_sim_runs_for_strategy($opt->strategy)
            ->sortBy(fn($sr) => $sr->result('vs_buy_hold'))
            ->map(fn($sr) => (float) $sr->strategy_options->find($opt->id)?->pivot->value)
            ->values();
    }

    public function first_step_interval_for_option(StrategyOption $opt)
    {
        $vals = $this->option_values($opt)->unique()->sort()->values();

        return count($vals) > 2 ? $vals[1] - $vals[0] : 'unknown';   
    }

    public function last_step_interval_for_option(StrategyOption $opt)
    {
        $vals = $this->option_values($opt)->unique()->sort()->values();

        return count($vals) > 2 ? $vals[count($vals) - 1] - $vals[count($vals) - 2] : 'unknown';   
    }

    // Returns a score between -1 and 1 which reflects the 'decisiveness' of the trend.
    // -1 meaning there is clearly a decisive downward trend and +1 meaning a decisive uptrend.
    // 0 means no obvious trend
    public function trend_score_for_option(StrategyOption $opt): float
    {
        // The view calls this method a few times as it stands, so I'm caching the result on the 
        // StrategyOption instance in case I never get round to improving the view side of things. 
        if ($opt->weighted_score) {
            return $opt->weighted_score;
        }

        $opt_vals = $this->option_values($opt); // These come sorted by vs_buy_hold

        $score = 0;

        $i = 0;

        while ($i < count($opt_vals) - 1) {
            // Score 0.5 for a 'flat' move, and -1 for a move down or 1 for a move up. 
            $score_for_move = $opt_vals[$i + 1] === $opt_vals[$i] ? 0.5 : $opt_vals[$i + 1] <=> $opt_vals[$i];
            
            $score += $score_for_move;            

            $i++;
        }

        $opt->weighted_score = $score / $opt_vals->count();

        return $opt->weighted_score;
    }

    public function get_recommendation_for_option(StrategyOption $opt)
    {
        return new NextBatchRecommendation($this, $opt);
    }

    public function no_recommendation_possible()
    {
        $that = $this; 

        return $this->get_varying_strategy_options()->filter(function($opt) use($that) {
            $that->trend_score_for_option($opt);

            return $opt->effect_on_trend() !== 0;  
        })->isEmpty();
    }

    public function batch_ancestry_list(): \Illuminate\Support\Collection
    {
        return $this->parent_batch ? collect([$this->parent_batch])->merge($this->parent_batch->batch_ancestry_list()) : collect([]);
    }
}
