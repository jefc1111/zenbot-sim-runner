<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Strategy;
use App\Models\StrategyOption;

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
        $input = request()->except('_token');

        $by_option_id = [];

        foreach ($input as $k => $v) {
            [ $option_id, $option_attribute ] = explode('-', $k);
            
            if (! array_key_exists($option_id, $by_option_id)) {
                $by_option_id[$option_id] = ['option_id' => $option_id];    
            }

            $by_option_id[$option_id][$option_attribute] = $v;
        }
        
        $sim_runs = array_map(function($item) {
            return new SimRun();
        }, $by_option_id);

        \Log::error($sim_runs);

        return view('create_sim_run_batch.confirm', 
            []
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
        //
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
