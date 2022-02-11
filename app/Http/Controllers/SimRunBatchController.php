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
    public function __construct()
    {
        $this->authorizeResource(SimRunBatch::class, 'sim_run_batch');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('sim_run_batches.list', [
            'show_all_columns' => false,
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
            'initial_name' => "Batch ".\Str::random(4),
            'initial_start_date' => date($date_format, strtotime('-13 days')),
            'initial_end_date' => date($date_format, strtotime('-12 days')),
            'autospawn_checkbox_enabled' => env('AUTO_SPAWN_BATCHES', true)
        ]);
    }

    public function select_strategies()
    {
        request()->session()->put('form_data', request()->all());

        return view('sim_run_batches.create.select_strategies', [
            'strategies' => Strategy::where('enabled', true)->get(),
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

        $sim_run_qty = $strategies->flatMap(fn($s) => $s->sim_runs)->count();

        return view('sim_run_batches.create.confirm', [ 
            'max_sim_run_qty' => env('MAX_SIM_RUN_QTY', 384),
            'sim_run_qty' => $sim_run_qty,
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

    public function copy(SimRunBatch $sim_run_batch)
    {
        request()->session()->put('form_data', array_merge(
            \Arr::except($sim_run_batch->attributesToArray(), [
                'name', 
                'created_at', 
                'updated_at', 
                'parent_batch_id'
            ]),
            [
                'name' => $sim_run_batch->name.' (copy)',
                'status' => 'ready'
            ]
        ));

        return view('sim_run_batches.create.select_strategies', [
            'strategies' => Strategy::all(),
            'batch' => new SimRunBatch(request()->session()->get('form_data')) // Just for display, not saving yet
        ]);
    }

    public function prune(SimRunBatch $sim_run_batch)
    {
        // Delete all sim runs where `result` is empty or `log` is not empty
        $sim_run_batch->sim_runs->whereNull('result')->each(fn($sr) => $sr->delete());
        $sim_run_batch->sim_runs->whereNotNull('log')->each(fn($sr) => $sr->delete());

        return back()->with('success', "Pruned incomplete and errored sim runs for batch \"$sim_run_batch->name\".");
    }

    public function reset(SimRunBatch $sim_run_batch)
    {
        $sim_run_batch->reset();
        
        return back()->with('success', "Reset batch \"$sim_run_batch->name\".");
    }

    public function get_status(SimRunBatch $sim_run_batch)
    {
        return [
            'batch_status' => $sim_run_batch->status,
            'percent_complete' => $sim_run_batch->percent_complete(),
            'qty_errored' => $sim_run_batch->sim_runs->filter(fn($sr) => $sr->status === 'error')->count(),
            'sim_run_statuses' => $sim_run_batch->sim_runs->map(function($sr) {
                return [
                    'id' => $sr->id,
                    'status' => $sr->status
                ];
            }) 
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SimRunBatch $sim_run_batch)
    {
        $varying_options_by_strategy_id = [];

        foreach ($sim_run_batch->get_all_strategies_used() as $strategy) {
            $varying_options_by_strategy_id[$strategy->id] = $sim_run_batch->get_varying_options_for_strategy($strategy);
        }

        return view('sim_run_batches.show.main', [
            'batch' => $sim_run_batch,
            'varying_options_by_strategy_id' => $varying_options_by_strategy_id,
            'chart_sim_runs' => $sim_run_batch->all_sim_runs_for_strategy($sim_run_batch->winning_strategy(), 'profit'),
            'chart_options' => $sim_run_batch->get_varying_options_for_winning_strategy(),
            'sim_runs_container' => $sim_run_batch
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
    public function destroy(SimRunBatch $sim_run_batch)
    {
        $msg = "Deleted batch \"$sim_run_batch->name\"";

        SimRun::where('sim_run_batch_id', $sim_run_batch->id)->delete();

        SimRunBatch::where('id', $sim_run_batch->id)->delete();

        // @todo: Remove zenbot log files

        return redirect()
        ->action([SimRunBatchController::class, 'index'])
        ->with('success', $msg); 
    }

    public function run(SimRunBatch $sim_run_batch)
    {
        if (! \Auth::user()->has_sim_time()) {
            return [
                'success' => false,
                'msg' => "You do not have sufficient sim time available"
            ];
        }

        return $sim_run_batch->run();
    }

    public function cancel(SimRunBatch $sim_run_batch)
    {
        return $sim_run_batch->cancel();
    }

    public function spawn_child_from(SimRunBatch $sim_run_batch)
    {
        $child_batch = $sim_run_batch->spawn_child();

        return redirect('/sim-run-batches/'.$child_batch->id);
    }

    public function get_backfill_log(SimRunBatch $sim_run_batch)
    {
        return [
            'success' => true,
            'lines' => $sim_run_batch->get_backfill_log_lines()
        ];
    }
}
