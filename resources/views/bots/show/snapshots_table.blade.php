<table id="snapshots-table" class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>id</th>
            <th>Start</th>
            <th>End</th>
            <th>Duration</th>
            @foreach($strategy->options as $option)
            <th>{{ $option->name }} {{ $option->unit ? '('.$option->unit.')' : null  }}</th>
            @endforeach
            <th>Run time</th>
            <th>Qty trades</th>
            <th>Buy & hold Profit</th>
            <th>Profit</th>              
            <th>vs. buy hold</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($strategy->sim_runs as $sim_run)
        <tr>
            <td>
                <a href="/sim-runs/{{ $sim_run->id }}">{{ $sim_run->id }}</a>
            </id>
            <td>{{ $sim_run->sim_run_batch->start_human() }}</td>
            <td>{{ $sim_run->sim_run_batch->end_human() }}</td>
            <td>{{ $sim_run->sim_run_batch->duration() }}</td>
            @foreach($strategy->options as $option)
            <td style="{{ $sim_run->get_runtime_value_for_option($option)->is_default_value_for_option ? 'color: #aaa;' : '' }} {{ $sim_run->get_runtime_value_for_option($option)->origin === 'user' ? 'background: #fafafa;' : '' }}">
                {{ $sim_run->get_runtime_value_for_option($option)->value }}
            </td>
            @endforeach
            @if($sim_run->result || $sim_run->log)
            <td>{{ gmdate("H\h i\m s\s", $sim_run->runtime) }}</td>            
            <td>{{ $sim_run->result('total_trades') }}</td>
            <td>{{ $sim_run->result_conv_pct('buy_hold_profit', 4) }}</td>
            <td>{{ $sim_run->result_conv_pct('profit', 4) }}</td>            
            <td>{{ $sim_run->result_pct('vs_buy_hold') }}</td>
            @else
            {!! str_repeat("<td></td>", 5) !!}    
            @endif
            <td>
                <span data-id="{{ $sim_run->id }}" class="sim-run-status text-{{ $sim_run->get_status_data($sim_run->status, 'style') }}">
                    {{ $sim_run->status }}
                </span>
            </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            {!! str_repeat("<th></th>", $strategy->options->count() + 10) !!}                        
        </tr>
    </tfoot>
</table>  

<script>    
    $(document).ready(function () {
        $('table#snapshots-table').DataTable();
    });
</script>
