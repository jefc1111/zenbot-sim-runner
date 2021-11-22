<?php

namespace App\Traits;

trait HasStatus {
    public $core_statuses = [
        'ready' => [
            'label' => 'ready to run',
            'style' => 'secondary'
        ],
        'running' => [
            'label' => 'running simulations',
            'style' => 'primary'
        ],
        'complete' => [
            'label' => 'complete',
            'style' => 'success'
        ],
        'error' => [
            'label' => 'error',
            'style' => 'danger'
        ],
    ];

    private function set_status(string $status): void
    {
        if (array_key_exists($status, $this->all_statae())) {
            $this->status = $status;

            $this->save();
        } else {
            \Log::error("Sim run batch status {$status} not found.");
        }
    }

    private function all_statae()
    {
        return array_merge(
            isset($this->statuses) ? $this->statuses : [],
            $this->core_statuses
        );
    }

    public function get_status_data($status, $attr): string
    {
        return $this->all_statae()[$status][$attr]; // Should guard this, eh
    }
}