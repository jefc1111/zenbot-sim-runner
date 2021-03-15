<?php

namespace App\Http\Controllers;
use App\Models\Exchange;
use App\Models\Product;
use App\Utility\ExchangeImporter;

use Illuminate\Http\Request;

class ExchangeController extends Controller
{
    public function index()
    {
        return view('exchanges.list', [
            'exchanges' => Exchange::all()
        ]);
    }

    public function show($id)
    {
        $exchange = Exchange::with('products')->findOrFail($id);

        if(request()->ajax()){
            return response()->json($exchange);
        } 

        return view('exchanges.show', [
            'exchange' => $exchange
        ]);
    }

    public function import_exchanges() {
        $importer = new ExchangeImporter(config('zenbot.location'));

        $importer->run();
    }
}
