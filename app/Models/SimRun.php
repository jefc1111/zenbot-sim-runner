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
    
    public function result_conv_pct(string $attr, int $precision = 2): ?string 
    {
        $res = $this->get_simresult_attr($attr) * 100;

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
        $errored_output = [];

        set_time_limit(900);

        $process = new Process($this->cmd_components());

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

        if ($success) {
            $this->result = $this->extract_json_result($process->getOutput());    
        
            $this->save();
        } else {
            $this->log = implode($errored_output);    
        
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
