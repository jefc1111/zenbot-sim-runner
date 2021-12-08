<?php

namespace App\Jobs;

use Spatie\WebhookClient\Jobs\ProcessWebhookJob as SpatieProcessWebhookJob;
use App\Models\Shop\SimTimeOrder;

class ProcessWebhookJob extends SpatieProcessWebhookJob
{
    public function __construct()
    {
        $this->queue = 'webhook';
    }

    public function handle()
    {
        $payload = json_decode($this->webhookCall)->payload;

        if ($payload->type === "InvoiceSettled") {
            $sim_time_order = SimTimeOrder::where('invoice_id', '=', $payload->invoiceId)->firstOrFail();

            $sim_time_order->mark_invoice_settled();
        } else {
            \Log::error("Webhook \"$payload->type\" type has not been implemented");
        }
        
        // perform the work here
    }
}