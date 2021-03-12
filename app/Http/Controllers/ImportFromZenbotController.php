<?php

namespace App\Http\Controllers;

use App\Utility\ExchangeImporter;
use App\Utility\StrategyImporter;
use Illuminate\Http\Request;

class ImportFromZenbotController extends Controller
{
    public function import_all() {
        $exchange_importer = new ExchangeImporter(config('zenbot.location'));

        $exchange_importer->run();

        $strategy_importer = new StrategyImporter(config('zenbot.location'));
        
        $strategy_importer->run();

        return redirect('/');
    }
}
