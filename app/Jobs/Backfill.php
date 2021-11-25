<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\SimRunBatch;
use Illuminate\Bus\Batchable;

class Backfill implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $sim_run_batch;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SimRunBatch $sim_run_batch)
    {
        $this->queue = 'backfill';

        $this->sim_run_batch = $sim_run_batch;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->sim_run_batch->set_status('queued');

        $this->sim_run_batch->do_backfill();        
    }
}
