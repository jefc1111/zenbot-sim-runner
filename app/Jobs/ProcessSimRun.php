<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\SimRun;
use Illuminate\Bus\Batchable;

class ProcessSimRun implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $sim_run;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SimRun $sim_run)
    {
        $this->queue = 'sim';

        $this->sim_run = $sim_run;

        if ($this->sim_run->status !== "pending-cancel") {
            $this->sim_run->set_status('queued');
        }        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {   
        if ($this->sim_run->status === "pending-cancel") {
            $this->sim_run->set_status("user-cancelled");
        } else {  
            $this->sim_run->run();
        }        
    }

    public function failed($exception)
    {
        $this->sim_run->set_status('error', $exception->getMessage());
    }
}
