<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use BTCPayServer\Client\Invoice;
use BTCPayServer\Client\InvoiceCheckoutOptions;
use BTCPayServer\Util\PreciseNumber;

use App\Models\Shop\SimTimeBundle;
use App\Models\Shop\SimTimeOrder;

class ShopController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {    
        return view('shop.main', [
            'sim_time_bundles' => SimTimeBundle::all()
        ]);
    }

    public function buy_sim_time_bundle($id)
    {
        $bundle = SimTimeBundle::findOrFail($id);

        $order = SimTimeOrder::create([
            'user_id' => \Auth::id(),
            'sim_time_bundle_id' => $bundle->id
        ]);

        $order->generate_invoice();        

        return view('shop.make_payment', [
            'bundle' => $bundle,
            'invoice_url' => $order->get_invoice_url()
        ]);
    }

    public function payment_webhook()
    {
        \Log::error('qwe');
        return 'Hello!';
    }






    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function test_stuff()
    {
        // Fill in with your BTCPay Server data.
        $apiKey = '71bbe655ea2ae4b1856616dd1f86b0ae67434ee4';
        $host = 'https://payments.zsr-app.com'; // e.g. https://your.btcpay-server.tld
        $storeId = '4ZzaQhcbdSTHYTYKhDhHjsFsCkuKtGofpDRwgchJfmPR';
        $invoiceId = '';
        $amount = 5.15 + mt_rand(0, 20);
        $currency = 'USD';
        $orderId = 'Test39939' . mt_rand(0, 1000);
        $buyerEmail = 'jefc_uk@hotmail.com';

        // Create a basic invoice.
        try {
            $client = new Invoice($host, $apiKey);
            print_r(
                $client->createInvoice(
                    $storeId,
                    $currency,
                    PreciseNumber::parseString($amount),
                    $orderId,
                    $buyerEmail
                )
            );
        } catch (\Throwable $e) {
            echo "Error: " . $e->getMessage();
        }

        // Create a more complex invoice with redirect url and metadata.
        try {
            $client = new Invoice($host, $apiKey);

            // Setup custom metadata. This will be visible in the invoice and can include
            // arbitrary data. Example below will show up on the invoice details page on
            // BTCPay Server.
            $metaData = [
            'buyerName' => 'John Doe',
            'buyerAddress1' => '43 South Beech Rd.',
            'buyerAddress2' => 'Door 3',
            'buyerCity' => 'Mount Prospect',
            'buyerState' => 'IL',
            'buyerZip' => '60056',
            'buyerCountry' => 'USA',
            'buyerPhone' => '001555664123456',
            'posData' => 'Data shown on the invoice details go here. Can be JSON encoded string',
            'itemDesc' => 'Can be a description of the purchased item.',
            'itemCode' => 'Can be SKU or item number',
            'physical' => false, // indicates if physical product
            'taxIncluded' => 2.15, // tax amount (included in the total amount).
            ];

            // Setup custom checkout options, defaults get picked from store config.
            $checkoutOptions = new InvoiceCheckoutOptions();
            $checkoutOptions
            ->setSpeedPolicy($checkoutOptions::SPEED_HIGH)
            ->setPaymentMethods(['BTC'])
            ->setRedirectURL('https://shop.yourdomain.tld?order=38338');

            print_r(
                $client->createInvoice(
                    $storeId,
                    $currency,
                    PreciseNumber::parseString($amount),
                    $orderId,
                    $buyerEmail,
                    $metaData,
                    $checkoutOptions
                )
            );
        } catch (\Throwable $e) {
            echo "Error: " . $e->getMessage();
        }

        // Create a top-up (0 initial amount) invoice.
        try {
            $client = new Invoice($host, $apiKey);
            print_r(
                $client->createInvoice(
                    $storeId,
                    $currency,
                    null,
                    $orderId,
                    $buyerEmail
                )
            );
        } catch (\Throwable $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
