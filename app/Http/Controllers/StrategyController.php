<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utility\StrategyImporter;
use App\Models\Strategy;
use App\Models\StrategyOption;


class StrategyController extends Controller
{
    public function index()
    {
        return view('list_strategies', ['strategies' => Strategy::all()]);
    }

    public function show($id)
    {
        return view('strategy_detail', ['strategy' => Strategy::findOrFail($id)]);
    }

    public function import_strategies() {
        Strategy::truncate();
        StrategyOption::truncate();

        $importer = new StrategyImporter(config('zenbot.location'));
        
        echo $importer->run();
    }
}
