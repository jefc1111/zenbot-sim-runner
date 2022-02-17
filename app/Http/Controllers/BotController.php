<?php

namespace App\Http\Controllers;
use App\Models\Exchange;
use App\Models\Product;
use App\Utility\ExchangeImporter;
use App\Models\BotManagement\ConfigParser;


use App\Jobs\BotStateGetter;

use Illuminate\Http\Request;

class BotController extends Controller
{
    public function index()
    {
        BotStateGetter::dispatch();        
    }

    public function show($id)
    {

    }
}
