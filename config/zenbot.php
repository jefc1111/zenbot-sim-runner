<?php

return [
    'location' => env('ZENBOT_LOCATION', '../../zenbot'),
    'node_executable' => env('NODE_EXECUTABLE', 'node'),
    'node_max_old_space_size' => env('NODE_MAX_OLD_SPACE_SIZE', 1024),
    'log_lines_to_keep' => env('ZENBOT_LOG_LINES_TO_KEEP', 25)
];
