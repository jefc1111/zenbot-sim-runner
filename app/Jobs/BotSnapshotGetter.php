<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\BotManagement\Bot;

class BotSnapshotGetter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private $bot
    ) {}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {   
        // Some bots keep a whole load of trade history which makes the call to the API very slow :-/    
        set_time_limit(180);

        $this->bot->take_snapshot();
    }

    public function failed($exception)
    {
        \Log::error($exception->getMessage());
    }
}
