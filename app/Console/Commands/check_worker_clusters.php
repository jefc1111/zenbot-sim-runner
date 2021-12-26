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
        if (! config('aws-zsr.do_ecs_remote_control')) {
            return false;
        }

        $aws_ecs = \AWS::createClient('ecs');

        $ecs_cluster_name = config('aws-zsr.cluster_name');

        $target_var = 'desiredCount';

        $ecs_queue_service_name_map = [
            'sim' => config('aws-zsr.sim_runner_service_name'),
            'backfill' => config('aws-zsr.backfill_service_name')
        ];

        $res = $aws_ecs->describeServices([
            'cluster' => $ecs_cluster_name,
            'services' => array_values($ecs_queue_service_name_map)
        ]);

        foreach ($ecs_queue_service_name_map as $queue_name => $ecs_service_name) {
            if ($res && $res->hasKey('services') && is_array($res->get('services'))) {
                $queue_size = \Queue::size($queue_name);
            
                \Log::error("$queue_name queue size $queue_size");

                $$target_var = $res->search("services.[?serviceName==`$ecs_service_name`].$target_var | [0]");

                \Log::error("$target_var for service '$ecs_service_name' is {$$target_var}");

                if ($queue_size === 0 && $$target_var > 0) {
                    \Log::error("Spinning down service '$ecs_service_name'");

                    $this->spin_down_cluster($aws_ecs, $ecs_service_name);                    
                } else if ($queue_size > 0 && $$target_var === 0) {
                    \Log::error("Spinning up service '$ecs_service_name'");

                    $this->spin_up_cluster($aws_ecs, $ecs_service_name);
                } else {
                    \Log::error("No cluster spinning action required for service '$ecs_service_name'");
                }
            } else {
                \Log::error("Could not retrieve '$target_var' from AWS describeServices response for service '$ecs_service_name'.");
            }
        }
    }

    private function spin_down_cluster($client, string $service_name)
    {
        $res = $client->updateService([
            'cluster' => config('aws-zsr.cluster_name'),
            'desiredCount' => 0,
            'service' => $service_name,
        ]);
    }

    private function spin_up_cluster($client, string $service_name)
    {
        $result = $client->updateService([
            'cluster' => config('aws-zsr.cluster_name'),
            'desiredCount' => 1,
            'service' => $service_name,
        ]);
    }
}
