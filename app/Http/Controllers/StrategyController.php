<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utility\StrategyImporter;
use App\Models\Strategy;
use App\Models\SimRun;
use App\Models\StrategyOption;


class StrategyController extends Controller
{
    public function index()
    {
        return view('strategies.list', [
            'strategies' => Strategy::all(),
            'total_sim_run_count' => SimRun::count()
        ]);
    }

    public function show($id)
    {
        $strategy = Strategy::with('sim_runs')->findOrFail($id);

        $cols_to_show = array_merge(
            range(
                1, $strategy->options->count() + 3
            ), 
            [ $strategy->options->count() + 9 ]
        );

        return view('strategies.show.main', [
            'strategy' => $strategy,
            'cols_to_show' => $cols_to_show
        ]);
    }

    public function import_strategies() {
        $importer = new StrategyImporter(config('zenbot.location'));
        
        $importer->run();
    }
}
