<hr>
<p>
    @if($batch->qty_strategies() === 1)
    All sim runs were for a single strategy - <strong>{{ $strategy->name }}</strong>
    @else
    <strong>{{ $strategy->name }}</strong> was the winning strategy. There were {{ $batch->qty_strategies() }} strategies used.
    @endif
</p>
<p>
    {{ $batch->get_varying_strategy_options()->count() }} strategy options had varying values over {{ $batch->all_sim_runs_for_strategy($strategy)->count() }} sim runs for the winning strategy
</p>
@foreach($batch->get_varying_strategy_options() as $opt)
<p>
    Strategy option <strong>{{ $opt->name }}</strong> had minimum value {{ $batch->option_values($opt)->min() }} and maximum value {{ $batch->option_values($opt)->max() }} with step interval of {{ $batch->first_step_interval_for_option($opt) }} (final interval {{ $batch->last_step_interval_for_option($opt) }})<br>
    INCREASING | DECREASING the value for <strong>{{ $opt->name }}</strong> appears to improve profitability<br>
    OR it is unclear what effect <strong>{{ $opt->name }}</strong> has on profitability<br>
    [show figures to back up the above statement]<br>
</p>
@endforeach
<hr>
<p>
    No recommendation could be made | It is recommended that a new batch is created with the following attributes: 
</p>
<p>
@foreach($batch->get_varying_strategy_options() as $strategy_option)
    <strong>{{ $strategy_option->name }}</strong>: min 10 max 100 step 20<br> 
@endforeach
</p>
<p>
    Warning! A child batch already exists.<br>
    <span class="text-small text-muted">
        Click <a href="#">here</a> to create a new batch based on the above recommendation.
    </span>
</p>