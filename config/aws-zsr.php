<?php

return [
    'cluster_name' => env('AWS_ECS_CLUSTER_NAME', 'zsr-workers'),
    'sim_runner_service_name' => env('AWS_ECS_SIM_RUNNER_SERVICE_NAME', 'zsr'),
    'backfill_service_name' => env('AWS_ECS_BACKFILL_SERVICE_NAME', 'zsr-backfill'),
    'do_ecs_remote_control' => env('AWS_DO_ECS_REMOTE_CONTROL', 'false')
];
