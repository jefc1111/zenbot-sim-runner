<x-layout>
    <div class="container">    
        <div class="row">            
            <div class="col-md-4">
                @include('sim_run_batches.metadata_snippet')
            </div>        
            <div class="col-md-2">
                <br/>
                <br/>
                <p>
                    <button type="button" class="btn btn-success btn-block" id="run">Run</button>
                </p>
                <br/>
                <p>
                    <a type="button" class="btn btn-info btn-block" href="copy/{{ $batch->id }}">Copy</a>
                    <small>Copy basic batch info only. Strategies can be selected and refined after copying.</small>
                </p>
            </div>
            <div class="col-md-6"></div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h4>Sim runs</h4>
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>Strategy</th>
                            <th>Qty trades</th>
                            <th>Profit</th>                
                            <th>vs. buy hold</th>
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
                            <td>{{ $sim_run->result('total_trades') }}</td>
                            <td>{{ $sim_run->result_pct('profit') }}</td>
                            <td>{{ $sim_run->result_pct('vs_buy_hold') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>                    
            </div>
        </div>
    </div>
    <script>
        $("#run").click(function() {
            $.get("/sim-run-batches/run/{{ $batch->id }}", function(res) {
                if (! res.success) {
                    
                } else {
                    //location.reload();
                }                
            });
        });
    </script>
</x-layout>