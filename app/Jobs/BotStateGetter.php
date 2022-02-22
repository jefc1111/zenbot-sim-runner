<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\BotManagement\Pm2ConfigParser;
use App\Models\BotManagement\Bot;

class BotStateGetter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {        
        if (! config('zenbot.bot_monitoring.active')) {
            \Log::info("Bot monitoring is disabled.");
            
            return;
        }

        // First contact our service which extracts config from pm2.
        // This adds any new bots to the database and sets any inactive
        // ones to inactive 
        // This runs synchronously so once we have updated our list of bots
        // to reflect reality, we can then go on and get the actual state from Zenbot
        Pm2ConfigParser::update_bots();

        // (it might be ok to run them in parallkel but getting `/trades` can be quite 
        // memory intensive so for now I am running them in series)
        // Each one taking a Bot and asking the relevant API endpoint (i.e. /trades:17000)
        // For data which then gets stashed as a BotSnapshot
        Bot::where('active', '=', 1)->get()->each(fn($bot) => $bot->take_snapshot());
    }

    public function failed($exception)
    {
        $exception->getMessage();
    }
}
