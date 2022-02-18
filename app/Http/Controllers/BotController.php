<?php

namespace App\Http\Controllers;
use App\Models\Exchange;
use App\Models\Product;
use App\Utility\ExchangeImporter;

use App\Jobs\BotStateGetter;

use Illuminate\Http\Request;

class BotController extends Controller
{
    public function index()
    {
        //new (App\Models\BotManagement\Pm2ConfigParser)->get_running_pm2_processes();
        
        BotStateGetter::dispatch();        
    }

    public function show($id)
    {

    }
}
