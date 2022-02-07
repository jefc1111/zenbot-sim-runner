<x-layout>
    <h2>Sim run batches</h2>
    <table id="sim-run-batches" class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>id</th>
                <th>Name</th>
                @if($show_all_columns)
                <th>Exchange</th>
                <th>Product</th>
                @endif
                <th>Asset</th>
                <th>Currency</th>
                <th>Date range</th>
                <th>Qty sim runs</th>
                <th>Qty strategies</th>
                <th>Best vs. buy hold</th>
                <th>Status</th>
                <th>Initiator</th>
                @if($show_all_columns)
                <th>User</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($sim_run_batches as $sim_run_batch)
            <tr>
                <td>{{ $sim_run_batch->id }}</id>
                <td>
                    <a href="/sim-run-batches/{{ $sim_run_batch->id }}">{{ $sim_run_batch->name }}</a>
                </td>
                @if($show_all_columns)
                <td>{{ $sim_run_batch->exchange->name }}</td>
                <td>{{ $sim_run_batch->product->name }}</td>
                @endif
                <td>{{ $sim_run_batch->product->asset }}</td>
                <td>{{ $sim_run_batch->product->currency }}</td>
                <td>{{ $sim_run_batch->humanised_date_range_with_duration() }}</td>
                <td>{{ $sim_run_batch->sim_runs->count() }}</td>
                <td>{{ $sim_run_batch->qty_strategies() }}</td>
                <td>{{ $sim_run_batch->best_vs_buy_hold(2, '%') }}</td>
                <td>{{ $sim_run_batch->status }}</td>
                <td>{{ $sim_run_batch->parent_batch_id ? 'system' : 'user' }}</td>
                @if($show_all_columns)
                <td>{{ $sim_run_batch->user ? $sim_run_batch->user->email : 'unknown user' }}</td>
                @endif                
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                {!! str_repeat("<th></th>", $show_all_columns ? 13 : 10) !!}    
            </tr>
        </tfoot>
    </table> 
    <a href="/sim-run-batches/create">Create</a>
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
            
            $('#sim-run-batches').DataTable({
                order: [[ 0, "desc" ]],
                initComplete: function() { 
                    addFilterSelects.call(this, {!! $show_all_columns ? "[2, 4, 5, 10, 11]" : "[2, 3, 8, 9]" !!}) 
                }
            });
        });
    </script>
    <style>
        td {
            white-space: nowrap;
            max-width: 200px;
            overflow: hidden;
        }
    </style>
</x-layout>