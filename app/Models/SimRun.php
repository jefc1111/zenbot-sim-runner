<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StrategyOption;
use App\Models\Strategy;
use App\Models\SimRunBatch;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Storage;

use App\Traits\HasStatus;
use App\Traits\InvokesZenbot;

class SimRun extends Model
{
    use HasFactory;
    use InvokesZenbot;
    use HasStatus;

    public $statuses = [
        'pending-cancel' => [
            'label' => 'cancel requested',
            'style' => 'warning',
            'spinner' => true
        ],
        'user-cancelled' => [
            'label' => 'cancelled by user',
            'style' => 'success',
            'spinner' => false
        ],
    ];

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

    private function cmd_components(array $zenbot_actions): array
    {
        return array_merge(
            $this->cmd_primary_components(), 
            $zenbot_actions,
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

    public function sim_cmd(): string
    {
        return implode(' ', $this->cmd_components(['sim']));
    }

    public function paper_trade_cmd(): string
    {
        return implode(' ', $this->cmd_components(['trade', '--paper']));
    }

    public function live_trade_cmd(): string
    {
        return implode(' ', $this->cmd_components(['trade']));
    }

    private function cmd_common_components(): array
    {
        $components = [
            $this->get_selector(),
            "--strategy={$this->strategy->name}",
            "--buy_pct={$this->sim_run_batch->buy_pct}",
            "--sell_pct={$this->sim_run_batch->sell_pct}",
            "--filename=".Storage::disk('zenbot-html-output')->path($this->get_html_output_path())
        ];

        return $components;
    }

    public function run()
    {
        $this->set_status('running');

        $start_time = time();       

        $process = new Process($this->cmd_components(['sim']));

        set_time_limit(config('zenbot.sim_timeout'));
        $process->setTimeout(config('zenbot.sim_timeout'));

        $process->setWorkingDirectory(config('zenbot.location'));

        \DB::disconnect('mysql'); // Prevent excess sleeping connections
        
        $process->start();

        Storage::disk('zenbot-html-output')->put($this->get_html_output_path(), '');
        \Storage::disk('zenbot-logs')->put($this->get_log_path(), '');  

        $last_msg = $this->write_log_file_and_get_last_msg($process, $this->get_log_path());

        if ($process->getExitCode() !== 0) {
            //\Log::error("Exit code was not zero. It was {$process->getExitCode()}.");
        }

        $success = $process->isSuccessful();

        if ($success) {
            $this->runtime = time() - $start_time;

            $this->sim_run_batch->user->available_seconds = $this->sim_run_batch->user->available_seconds - $this->runtime;

            $this->result = $this->extract_json_result($this->get_log_file());    
        
            $this->set_status('complete'); // This does a save
        } else {
            $this->set_status('error', $last_msg); // This does a save

            throw new ProcessFailedException($process);
        }

        $this->sim_run_batch->user->save();    
    }

    private function get_log_path()
    {
        return "zenbot-logs/{$this->get_filename()}.log";
    }

    private function get_html_output_path()
    {
        return "zenbot-html-output/{$this->get_filename()}.html";
    }

    public function get_zenbot_html_output()
    {
        return Storage::disk('zenbot-html-output')->get($this->get_html_output_path());
    }

    public function has_zenbot_html_output()
    {
        return Storage::disk('zenbot-html-output')->exists($this->get_html_output_path());
    }

    private function get_filename()
    {
        return "$this->sim_run_batch_id-sim-run-$this->id";
    }

    private function get_log_file()
    {
        return Storage::disk('zenbot-logs')->get($this->get_log_path());
    }

    public function delete_log()
    {
        Storage::disk('zenbot-logs')->delete($this->get_log_path());
    }

    public function get_log_lines()
    {
        return $this->tail_log_file($this->get_log_path());
    }

    // Extracts the chunk of JSON from the entire log output for a sim run
    private function extract_json_result(string $raw_cmd_output): object
    {
        $start = strpos($raw_cmd_output, "{");
        $end = strrpos($raw_cmd_output, "}", -1);
        
        return json_decode(
            substr($raw_cmd_output, $start, $end - $start + 1)
        );
    }

    public function can_be_cancelled()
    {
        return $this->status === 'ready' || $this->status === 'queued';
    }

    public function cancel()
    {
        $this->set_status("pending-cancel");
    }
}
