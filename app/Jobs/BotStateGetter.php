<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Queue\Middleware\WithoutOverlapping;

use App\Models\BotManagement\Pm2ConfigParser;

class BotStateGetter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware()
    {
        return [
            new WithoutOverlapping()
        ];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {        
        Pm2ConfigParser::update_bots();
    }

    public function failed($exception)
    {
        $exception->getMessage();
    }
}
