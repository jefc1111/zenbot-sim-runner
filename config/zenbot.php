<?php

return [
    'location' => env('ZENBOT_LOCATION', '../../zenbot'),
    'node_executable' => env('NODE_EXECUTABLE', 'node'),
    'node_max_old_space_size' => env('NODE_MAX_OLD_SPACE_SIZE', 1024),
    'log_lines_to_keep' => env('ZENBOT_LOG_LINES_TO_KEEP', 25),
    'backfill_timeout' => env('ZENBOT_BACKFILL_TIMEOUT', 28800),
    'sim_timeout' => env('ZENBOT_SIM_TIMEOUT', 28800),
    'bot_monitoring' => [
        'active' => env('ZENBOT_BOT_MONITORING_ACTIVE', false),
        'manager_url' => env('ZENBOT_BOT_MANAGER_URL'),
        'base_url' => env('ZENBOT_BOT_BASE_URL')
    ]
];
