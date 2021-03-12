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
        return view('exchanges.list', ['exchanges' => Exchange::all()]);
    }

    public function show($id)
    {
        return view('exchanges.show', ['exchange' => Exchange::findOrFail($id)]);
    }

    public function import_exchanges() {
        $importer = new ExchangeImporter(config('zenbot.location'));

        $importer->run();
    }
}
