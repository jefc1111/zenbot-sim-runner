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
        $apiKey = '71bbe655ea2ae4b1856616dd1f86b0ae67434ee4';
        $host = 'https://payments.zsr-app.com'; // e.g. https://your.btcpay-server.tld
        $storeId = '4ZzaQhcbdSTHYTYKhDhHjsFsCkuKtGofpDRwgchJfmPR';
        
        $amount = $this->sim_time_bundle->cost;
        
        $currency = $this->sim_time_bundle->currency;
        $order_id = $this->id;
        
        // Create a basic invoice.
        try {
            $client = new Invoice($host, $apiKey);
            
            $this->invoice = $client->createInvoice(
                $storeId,
                $currency,
                PreciseNumber::parseString($amount),
                $order_id,
                \Auth::user()->email
            );            
        } catch (\Throwable $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function get_invoice_url()
    {
        return $this->invoice->offsetGet('checkoutLink');
    }
}
