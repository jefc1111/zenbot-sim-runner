<?php

namespace App\Models\Shop;

use BTCPayServer\Client\Invoice;
use BTCPayServer\Client\InvoiceCheckoutOptions;
use BTCPayServer\Util\PreciseNumber;

use Illuminate\Database\Eloquent\Model;

class SimTimeOrder extends Model
{
    protected $guarded = ['id'];

    private $invoice;

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function sim_time_bundle()
    {
        return $this->belongsTo(\App\Models\Shop\SimTimeBundle::class);
    }

    public function generate_invoice(): bool
    {   
        try {
            $client = new Invoice(
                config('btcpay.host'), 
                config('btcpay.api_key')
            );
            
            $checkout_options = new InvoiceCheckoutOptions();
            $checkout_options->setRedirectURL(route("shop"));

            $this->invoice = $client->createInvoice(
                config('btcpay.store_id'),
                $this->sim_time_bundle->currency,
                PreciseNumber::parseString($this->sim_time_bundle->cost),
                $this->id,
                \Auth::user()->email,
                [],
                $checkout_options
            );             
   
            $this->invoice_id = $this->invoice->offsetGet('id');

            $this->status = 'invoice-created';

            $this->save();
        } catch (\Throwable $e) {
            \Log::error("Error: " . $e->getMessage());

            return false;
        }

        return true;
    }

    public function get_invoice_url()
    {
        return $this->invoice->offsetGet('checkoutLink');
    }

    public function mark_invoice_settled()
    {
        $this->user->available_seconds += $this->sim_time_bundle->get_bundle_time_as_seconds();

        $this->user->save();

        $this->status = 'complete';

        $this->save();
    }
}
