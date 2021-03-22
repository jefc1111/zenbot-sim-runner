<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StrategyOption;
use App\Models\Strategy;
use App\Models\SimRunBatch;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class SimRun extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    protected $casts = [
        'result' => 'array'
    ];

    public array $unsaved_strategy_option_data = []; // key is option id, value is string value

    public function strategy_options()
    {
        return $this->belongsToMany(StrategyOption::class)->withPivot('value');
    }

/*
CUNT
NEXT:
Need to conform that POST data _is_ being truncated, as suspected, when there is large qty of sim runs to save  
*/

    public function strategy()
    {
        return $this->belongsTo(Strategy::class);
    }

    public function sim_run_batch()
    {
        return $this->belongsTo(SimRunBatch::class);
    }

    public function getVsBuyHoldAttribute()
    {
        return $this->result ? $this->result['simresults']['vs_buy_hold'] : null;
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
        return implode(' ', $this->cmd_components());
    }

    private function cmd_common_components(): array
    {
        $components = [
            config('zenbot.node_executable'), 
            config('zenbot.location').'/zenbot.js', 
            'sim',
            $this->sim_run_batch->get_selector(),
            "--strategy={$this->strategy->name}",
            "--start={$this->sim_run_batch->start->format('Y-m-d')}", 
            "--end={$this->sim_run_batch->end->format('Y-m-d')}",
            "--buy_pct={$this->sim_run_batch->buy_pct}",
            "--sell_pct={$this->sim_run_batch->sell_pct}",
            "--filename=none",
            "--silent",
        ];

        return $components;
    }

    private function cmd_option_components(): array
    {
        // Including any value for `period_length` was causing a `Error: invalid bucket size spec:` error 
        // at cmd line
        // I _think_ `period_length` is just a dupe of `period` anyway. Maybe. 
        return $this->strategy_options
        ->filter(fn($o) => $o->name !== 'period_length')
        ->map(fn($o) => "--$o->name={$o->value}")->toArray();
    }

    private function cmd_components(): array
    {
        return array_merge(
            $this->cmd_common_components(), 
            $this->cmd_option_components()
        );
    }

    public function run()
    {
        $process = new Process($this->cmd_components());

        $process->setWorkingDirectory(config('zenbot.location'));

        $process->run();

        if ($process->getExitCode() !== 0) {
            //\Log::error("Exit code was not zero. It was {$process->getExitCode()}.");
        }

        $success = $process->isSuccessful();

        if ($success) {
            $this->result = $this->extract_json_result($process->getOutput());    
        
            $this->save();
        } else {
            //throw new ProcessFailedException($process);
        }         

        return [
            'success' => $success,
            'error' => $process->getErrorOutput(),
            'output' => $this->result
        ];
    }

    private function extract_json_result(string $raw_cmd_output): object
    {
        // The third argument to `explode()` is to make it only split on the first occurence of '{'
        // Also, the split removes the '{' so we have to reinstate it.
        return json_decode('{'.explode('{', $raw_cmd_output, 2)[1]);
    }
}
