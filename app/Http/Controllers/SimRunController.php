<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SimRun;
use App\Jobs\ProcessSimRun;

class SimRunController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(SimRunBatch::class, 'sim_run');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
  
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
  
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SimRun $sim_run)
    {
        return view('sim_runs.show.main', [
            'sim_run' => $sim_run
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

    public function run(SimRun $sim_run)
    {
        if (! \Auth::user()->has_sim_time()) {
            return [
                'success' => false,
                'msg' => "You do not have sufficient sim time available"
            ];
        }

        ProcessSimRun::dispatch($sim_run);
        
        $queue_size = \Queue::size();

        return [
            'success' => true,
            'msg' => "Sim run submitted to queue in position $queue_size"
        ];
    }

    public function get_log(SimRun $sim_run)
    {
        return [
            'success' => true,
            'lines' => $sim_run->get_log_lines()
        ];
    }
}
