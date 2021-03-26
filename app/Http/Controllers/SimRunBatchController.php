<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Strategy;
use App\Models\StrategyOption;
use App\Models\SimRunBatch;
use App\Models\SimRun;
use App\Models\Exchange;

class SimRunBatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('sim_run_batches.list', [
            'sim_run_batches' => SimRunBatch::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $date_format = 'Y-m-d';

        return view('sim_run_batches.create.init', [
            'exchanges' => Exchange::all(),
            'initial_name' => "Sim run batch ".\Str::random(4),
            'initial_start_date' => date($date_format, strtotime('-30 days')),
            'initial_end_date' => date($date_format, strtotime('-1 days'))
        ]);
    }

    public function select_strategies()
    {
        \Log::error('SELECT STRATEGIES');
        \Log::error(request()->input());
        request()->flashExcept('_token');

        return view('sim_run_batches.create.select_strategies', [
            'strategies' => Strategy::all()
        ]);
    }

    public function refine_strategies() 
    {        
        \Log::error('REFINE STRATEGIES');
        \Log::error(request()->input());
        \Log::error(request()->old());
        request()->session()->reflash();

        return view('sim_run_batches.create.refine_strategies', 
            ['strategies' => Strategy::findMany(request()->get('strategies'))]
        );
    }

    public function confirm()
    {
        \Log::error('CONFIRM');
        \Log::error(request()->old());
        request()->session()->reflash();

        // Ask sim run batch to spawn set of sim runs 
        // Give sim run batch the input data
        $strategies = SimRunBatch::make_sim_runs(request()->except('_token'));

        return view('sim_run_batches.create.confirm', 
            [ 'strategies' => $strategies ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {      
        \Log::error('STORE');  
        \Log::error(request()->old());
        $sim_run_batch = SimRunBatch::create(request()->old());

        $input_data = request()->except('_token');

        $input_data_as_entry_per_sim_run = [];

        foreach ($input_data as $k => $v) {
            [ $index, $option_id ] = explode('-', $k);

            $input_data_as_entry_per_sim_run[$index][$option_id] = ['value' => $v];
        }

        foreach ($input_data_as_entry_per_sim_run as $options_for_sim_run) {
            $strategy_id = StrategyOption::findOrFail(array_key_first($options_for_sim_run))->strategy_id;

            SimRun::create([
                'strategy_id' => $strategy_id,
                'sim_run_batch_id' => $sim_run_batch->id
            ])->strategy_options()->sync($options_for_sim_run);
        }

        return redirect('/sim-run-batches/'.$sim_run_batch->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('sim_run_batches.show', [
            'batch' => SimRunBatch::findOrFail($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function run($id)
    {
        $sim_run_batch = SimRunBatch::findOrFail($id);

        $sim_run_batch->run();
    }
}
