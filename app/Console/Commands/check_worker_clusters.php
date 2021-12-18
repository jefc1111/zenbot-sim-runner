<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class check_worker_clusters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ecs-clusters:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks if is possible to stop all tasks across each ECS service';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $aws_ecs = \AWS::createClient('ecs');

        $ecs_cluster_name = config('aws-zsr.cluster_name');

        $target_var = 'desiredCount';

        $ecs_service_names = [
            config('aws-zsr.sim_runner_service_name'),
            config('aws-zsr.backfill_service_name')
        ];

        $res = $aws_ecs->describeServices([
            'cluster' => $ecs_cluster_name,
            'services' => $ecs_service_names
        ]);

        foreach ($ecs_service_names as $ecs_service_name) {
            if ($res && $res->hasKey('services') && is_array($res->get('services'))) {
                $$target_var = $res->search("services.[?serviceName==`$ecs_service_name`].$target_var | [0]");

                \Log::error("$target_var for service '$ecs_service_name' is {$$target_var}");
            } else {
                \Log::error("Could not retrieve '$target_var' from AWS describeServices response for service '$ecs_service_name'.");
            }
        }

        foreach (['backfill', 'sim'] as $queue_name) {
            $queue_size = \Queue::size($queue_name);
            
            \Log::error("$queue_name queue size $queue_size");

            // if $queue_size === 0 && desiredCount > 1 set desiredCount to 0  
        } 
    }
}
