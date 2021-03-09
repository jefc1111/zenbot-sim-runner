<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Strategy;
use App\Models\SimRunBatch;
use App\Models\SimRun;

class SimRunBatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function select_strategies()
    {
        return view('create_sim_run_batch.select_strategies', ['strategies' => Strategy::all()]);
    }

    public function refine_strategies() 
    {
        return view('create_sim_run_batch.refine_strategies', 
            ['strategies' => Strategy::findMany(request()->get('strategies'))]
        );
    }

    public function confirm()
    {
        // Ask sim run batch to spawn set of sim runs 
        // Give sim run batch the input data
        $strategies = SimRunBatch::make_sim_runs(request()->except('_token'));

        return view('create_sim_run_batch.confirm', 
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
        $sim_run_batch = SimRunBatch::create([
            'exchange_id' => 1,
            'product_id' => 2,
            'days' => 30,
            'start' => '2020-01-01',
            'end' => '2020-01-31',
            'buy_pct' => 50,
            'sell_pct' => 50
        ]);

        $input_data = request()->except('_token');

        $input_data_as_entry_per_sim_run = [];

        foreach ($input_data as $k => $v) {
            [ $index, $option_id ] = explode('-', $k);

            $input_data_as_entry_per_sim_run[$index][$option_id] = $v;
        }

        foreach ($input_data_as_entry_per_sim_run as $options_for_sim_run) {
            $sim_run = SimRun::create([
                'strategy_id' => 222,
                'sim_run_batch_id' => $sim_run_batch->id
            ]);

            foreach ($options_for_sim_run as $option_id => $option_value) {

            }
        }


        dd($input_data_as_entry_per_sim_run);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
}
