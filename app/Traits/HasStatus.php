<?php

namespace App\Traits;

trait HasStatus {
    public $core_statuses = [
        'queued' => [
            'label' => 'queued',
            'style' => 'info',
            'spinner' => false
        ],
        'ready' => [
            'label' => 'ready to run',
            'style' => 'secondary',
            'spinner' => false
        ],
        'running' => [
            'label' => 'running simulations',
            'style' => 'primary',
            'spinner' => true
        ],
        'complete' => [
            'label' => 'complete',
            'style' => 'success',
            'spinner' => false
        ],
        'error' => [
            'label' => 'error',
            'style' => 'danger',
            'spinner' => false
        ],
    ];

    public function set_status(string $status, string $msg = ''): void
    {
        if (array_key_exists($status, $this->all_statuses())) {
            $this->status = $status;

            $this->save();
        } else {
            \Log::error("Sim run batch status {$status} not found.");
        }

        if ($status === 'error') {
            $this->notify_of_error($msg);
        }
    }

    private function notify_of_error(string $msg): void
    {
        $class_name = (new \ReflectionClass($this))->getShortName();

        try {
            $res = \Illuminate\Support\Facades\Http::post(config('discord.error_webhook_url'), [
                'content' => "A $class_name completed in error",
                'embeds' => [
                    [
                        'title' => "$class_name $this->name ($this->id)",
                        'description' => $msg,
                        'color' => '16711680',
                    ]
                ],
            ]);
        } catch (\Throwable $e) {
            \Log::error("Error sending message to Discord: " . $e->getMessage());
        }
    }

    public function all_statuses()
    {
        return array_merge(
            isset($this->statuses) ? $this->statuses : [],
            $this->core_statuses
        );
    }

    public function get_status_data($status, $attr = null)
    {
        if (is_null($attr)) {
            return $this->all_statuses()[$status]; 
        }

        return $this->all_statuses()[$status][$attr]; // Should guard this, eh
    }
}