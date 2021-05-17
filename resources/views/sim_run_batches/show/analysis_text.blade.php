<hr>
<h1>Note: Everything below is a work in progress. </h1>
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
    The trend score is {{ $batch->trend_score_for_option($opt) }}<br>
    @if ($opt->effect_on_trend() === 0)
    It is unclear what, if any, effect <strong>{{ $opt->name }}</strong> has on profitability<br>
    @else
    <strong>{{ $batch->trend_score_for_option($opt) < 0 ? 'Decreasing' : 'Increasing' }}</strong> the value for <strong>{{ $opt->name }}</strong> appears to improve profitability<br>
    @endif
</p>
@endforeach
<hr>
<p>
    @if($batch->no_recommendation_possible())
    No recommendation could be made
    @else
    It is recommended that a new batch is created with the following attributes: 
    @endif
</p>
<p>
    <div class="col-md-6">
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>Option</th>
                    <th>Min</th>
                    <th>Max</th>
                    <th>Step</th>
                </tr>
            </thead>
            <tbody>
                @foreach($batch->get_varying_strategy_options() as $strategy_option)
                <tr>                
                    <?php $rec = $batch->get_recommendation_for_option($strategy_option) ?>    
                    <td>{{ $strategy_option->name }}</td>
                    <td>{{ $rec->min }}</td>
                    <td>{{ $rec->max }}</td>
                    <td>{{ $rec->step }}</td> 
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</p>
<p>
    Warning! A child batch already exists.<br>
    <span class="text-small text-muted">
        Click <a href="#">here</a> to create a new batch based on the above recommendation.
    </span>
</p>