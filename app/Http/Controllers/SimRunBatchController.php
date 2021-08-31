<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Strategy;
use App\Models\StrategyOption;
use App\Models\SimRunBatch;
use App\Models\SimRun;
use App\Models\Exchange;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

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
            'sim_run_batches' => Auth::user()->hasRole('admin') 
            ? SimRunBatch::all() 
            : Auth::user()->sim_run_batches
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
            'initial_start_date' => date($date_format, strtotime('-13 days')),
            'initial_end_date' => date($date_format, strtotime('-12 days')),
            'autospawn_checkbox_enabled' => env('AUTO_SPAWN_BATCHES', true)
        ]);
    }

    public function select_strategies()
    {
        request()->session()->put('form_data', request()->all());

        return view('sim_run_batches.create.select_strategies', [
            'strategies' => Strategy::all(),
            'batch' => new SimRunBatch(request()->session()->get('form_data')) // Just for display, not saving yet
        ]);
    }

    public function refine_strategies() 
    {             
        return view('sim_run_batches.create.refine_strategies', [
            'strategies' => Strategy::findMany(request()->get('strategies')),
            'batch' => new SimRunBatch(request()->session()->get('form_data')) // Just for display, not saving yet
        ]);
    }

    public function confirm()
    {
        // Ask sim run batch to spawn set of sim runs 
        // Give sim run batch the input data
        $strategies = SimRunBatch::make_sim_runs(request()->except('_token'));

        return view('sim_run_batches.create.confirm', [ 
            'strategies' => $strategies,
            'batch' => new SimRunBatch(request()->session()->get('form_data')) // Just for display, not saving yet
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {      
        $sim_run_batch_data = request()->session()->get('form_data');

        $sim_run_batch_data['user_id'] = Auth::user()->id;

        $sim_run_batch = SimRunBatch::create($sim_run_batch_data); // Now save it to the db

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

    public function copy($id)
    {
        $batch = SimRunBatch::findOrFail($id);

        if ($batch->user_id != Auth::user()->id) {
            abort(403);
        }

        request()->session()->put('form_data', array_merge(
            \Arr::except($batch->attributesToArray(), [
                'name', 
                'created_at', 
                'updated_at', 
                'parent_batch_id'
            ]),
            [
                'name' => $batch->name.' (copy)'
            ]
        ));

        return view('sim_run_batches.create.select_strategies', [
            'strategies' => Strategy::all(),
            'batch' => new SimRunBatch(request()->session()->get('form_data')) // Just for display, not saving yet
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $batch = SimRunBatch::findOrFail($id);

        if (! Auth::user()->hasRole('admin') && $batch->user_id != Auth::user()->id) {
            abort(403);
        }

        return view('sim_run_batches.show.main', [
            'batch' => $batch
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
        if (! \Auth::user()->has_sim_time()) {
            return [
                'success' => false,
                'msg' => "You do not have sufficient sim time available"
            ];
        }
        
        $sim_run_batch = SimRunBatch::findOrFail($id);

        if ($sim_run_batch->user_id != Auth::user()->id) {
            abort(403);
        }

        return $sim_run_batch->run();
    }

    public function spawn_child_from($id)
    {
        $source_batch = SimRunBatch::findOrFail($id);

        if ($source_batch->user_id != Auth::user()->id) {
            abort(403);
        }

        $child_batch = $source_batch->spawn_child();

        return redirect('/sim-run-batches/'.$child_batch->id);
    }
}
