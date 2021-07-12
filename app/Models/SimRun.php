<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StrategyOption;
use App\Models\Strategy;
use App\Models\SimRunBatch;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Traits\InvokesZenbot;

class SimRun extends Model
{
    use HasFactory;
    use InvokesZenbot;

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

    public function strategy()
    {
        return $this->belongsTo(Strategy::class);
    }

    public function sim_run_batch()
    {
        return $this->belongsTo(SimRunBatch::class);
    }

    public function get_simresult_attr(string $attr): ?string
    {
        return $this->result ? $this->result['simresults'][$attr] : null;
    }

    public function get_selector(): string
    {
        return $this->sim_run_batch->get_selector();
    }

    public function result(string $attr): ?string
    {
        $res = $this->get_simresult_attr($attr);

        return ! is_null($res) ? $res : null;
    }

    public function result_pct(string $attr, int $precision = 2): ?string 
    {
        $res = $this->get_simresult_attr($attr);

        return ! is_null($res) ? round($res, $precision)."%" : null;
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

    private function cmd_components(): array
    {
        return array_merge(
            $this->cmd_primary_components(), 
            $this->cmd_common_components(), 
            $this->cmd_date_components($this->sim_run_batch),
            $this->cmd_option_components()
        );
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

    public function cmd(): string
    {
        return implode(' ', $this->cmd_components());
    }

    private function cmd_common_components(): array
    {
        $components = [
            'sim',
            $this->get_selector(),
            "--strategy={$this->strategy->name}",
            "--buy_pct={$this->sim_run_batch->buy_pct}",
            "--sell_pct={$this->sim_run_batch->sell_pct}",
            "--filename=none",
            "--silent",
        ];

        return $components;
    }

    public function run()
    {
        $start_time = time();

        $errored_output = [];        

        $process = new Process($this->cmd_components());

        set_time_limit(900);
        $process->setTimeout(900);

        $process->setWorkingDirectory(config('zenbot.location'));

        $process->run(function($type, $buffer) use(&$errored_output) {
            if (Process::ERR === $type) {
                $errored_output[] = $buffer;
            } else {
                //$success_output[] = $buffer;
            }
        });

        if ($process->getExitCode() !== 0) {
            //\Log::error("Exit code was not zero. It was {$process->getExitCode()}.");
        }

        $success = $process->isSuccessful();

        $this->runtime = time() - $start_time;

        $this->sim_run_batch->user->available_seconds = $this->sim_run_batch->user->available_seconds - $this->runtime;

        $this->sim_run_batch->user->save();

        if ($success) {
            $this->result = $this->extract_json_result($process->getOutput());    
        
            $this->save();
        } else {
            $str_error_output = implode($errored_output);
            
            $this->log = $str_error_output;
        
            $this->save();
            
            throw new ProcessFailedException($process);
        }         
    }

    private function extract_json_result(string $raw_cmd_output): object
    {
        // The third argument to `explode()` is to make it only split on the first occurence of '{'
        // Also, the split removes the '{' so we have to reinstate it.
        return json_decode('{'.explode('{', $raw_cmd_output, 2)[1]);
    }
}
