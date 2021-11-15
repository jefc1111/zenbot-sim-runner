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

    public function generate_invoice()
    {   
        $amount = $this->sim_time_bundle->cost;
        
        $currency = $this->sim_time_bundle->currency;
        $order_id = $this->id;

        try {
            $client = new Invoice(env('BTCPAY_HOST'), env('BTCPAY_API_KEY'));

            $this->invoice = $client->createInvoice(
                env('BTCPAY_STORE_ID'),
                $currency,
                PreciseNumber::parseString($amount),
                $order_id,
                \Auth::user()->email
            );             
   
            $this->invoice_id = $this->invoice->offsetGet('id');

            $this->status = 'invoice-created';

            $this->save();
        } catch (\Throwable $e) {
            \Log::error("Error: " . $e->getMessage());
        }
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
