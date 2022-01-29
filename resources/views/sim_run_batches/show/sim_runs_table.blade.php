<table id="sim-runs-table" class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>id</th>
            <th>Strategy</th>
            <th>Options</th>
            <th>Run time</th>
            <th>Qty trades</th>
            <th>Buy & hold Profit</th>
            <th>Profit</th>              
            <th>vs. buy hold</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($batch->sim_runs as $sim_run)
        <tr>
            <td>
                <a href="/sim-runs/{{ $sim_run->id }}">{{ $sim_run->id }}</a>
            </id>
            <td>
                <a href="/strategies/{{ $sim_run->strategy->id }}">{{ $sim_run->strategy->name }}</a>
            </td>
            <td>
                @include('sim_run_batches.show.sim_run_options_table')
            </td>
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
            {!! str_repeat("<th></th>", 9) !!}                        
        </tr>
    </tfoot>
</table>  

<script>    
    $(document).ready(function () {
        var addFilterSelects = function(columnsToFilter) {
            this.api().columns(columnsToFilter).every(function () {
                var column = this;

                var select = $('<select><option value=""></option></select>')
                    .appendTo($(column.footer()).empty())
                    .on('change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val().trim()
                        );

                        column
                            .search(val, false, false)
                            .draw();
                        }
                    );

                column
                    .data()
                    .map(function(e) { return $("<span>" + e + "</span>").text() })
                    .unique()
                    .sort()
                    .each( function (d, j) {
                        select.append( '<option value="'+d+'">'+d+'</option>' )
                    } 
                );                    
            });
        }

        $('table#sim-runs-table').DataTable({
            initComplete: function() { addFilterSelects.call(this, [1, 7]) }
        });
    });
</script>
