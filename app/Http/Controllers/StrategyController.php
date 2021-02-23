<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use App\Utility\StrategyImporter;
use App\Models\Strategy;


class StrategyController extends Controller
{
    public function index()
    {
        return view('list_strategies', ['strategies' => Strategy::all()]);
    }

    public function import_strategies() {
        $importer = new StrategyImporter(config('zenbot.location'));
        
        echo $importer->run();
    }
}
