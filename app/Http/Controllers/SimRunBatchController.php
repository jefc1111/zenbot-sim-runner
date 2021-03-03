<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Strategy;
use App\Models\SimRunBatch;

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
