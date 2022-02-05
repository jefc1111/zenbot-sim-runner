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
        return view('strategies.list', ['strategies' => Strategy::all()]);
    }

    public function show($id)
    {
        return view('strategies.show.main', ['strategy' => Strategy::findOrFail($id)]);
    }

    public function import_strategies() {
        $importer = new StrategyImporter(config('zenbot.location'));
        
        $importer->run();
    }
}
