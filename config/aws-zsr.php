<?php

return [
    'cluster_name' => env('AWS_ECS_CLUSTER_NAME', 'zsr-workers'),
    'sim_runner_service_name' => env('AWS_ECS_SIM_RUNNER_SERVICE_NAME', 'zsr'),
    'backfill_service_name' => env('AWS_ECS_BACKFILL_SERVICE_NAME', 'zsr-backfill')
];
