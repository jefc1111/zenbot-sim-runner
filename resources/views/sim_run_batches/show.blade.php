<x-layout>
    <div class="row">
        <div class="col-md-6">
            <h3>
                {{ $batch->name }}
            </h3>                
        </div>
        <div style="text-align: right; " class="text-muted col-md-6">
            <h4>
                {{ $batch->get_pair_name() }} {{ $batch->humanised_date_range() }}
            </h4>
        </div>
    </div>
    <div class="">
        <ul id="sim-run-batch-tab-header" class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a id="overview-tab" data-toggle="tab" class="nav-link active" href="#overview">Overview</a>
            </li>
            <li class="nav-item">
                <a id="sim-runs-tab" data-toggle="tab" class="nav-link" href="#sim-runs">Sim runs ({{ $batch->sim_runs->count() }})</a>
            </li>
            <li class="nav-item">
                <a id="analysis-tab" data-toggle="tab" class="nav-link" href="#analysis">Analysis</a>
            </li>
        </ul>
    </div>
    <div class="">
        <div class="tab-content" id="sim-run-batch-tab-content">
            <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                <div class="row">
                    @include('sim_run_batches.metadata_snippet')
                </div>      
                <div class="row">
                    <button type="button" class="btn btn-success" id="run">Run</button>    
                    <br/>            
                    <a type="button" class="btn btn-primary" href="copy/{{ $batch->id }}">Copy</a>
                    <small>Copy basic batch info only. Strategies can be selected and refined after copying.</small>
                </div>
            </div>
            <div class="tab-pane fade" id="sim-runs" role="tabpanel" aria-labelledby="sim-runs-tab">
                <div class="row">
                    <div class="col-md-12">
                        <table id="sim-runs-table" class="table table-sm table-bordered">
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
            <div class="tab-pane fade" id="analysis" role="tabpanel" aria-labelledby="analysis-tab">
                <div id="varying-options-chart" style="width:100%; height:400px;"></div>
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

        $(document).ready(function () {
            $('table#sim-runs-table').DataTable();

            const chart = Highcharts.chart('varying-options-chart', {
                chart: {
                    type: 'line'
                },
                credits: {
                    enabled: false
                },
                title: {
                    text: 'Sim runs for strategy "{{ $batch->winning_strategy()->name }}"'
                },
                xAxis: {
                    categories: {!! json_encode($batch->all_sim_runs_for_winning_strategy()->pluck('id')->values()) !!}
                },
                yAxis: [                    
                    {
                        title: {
                            text: 'Vs buy & hold'
                        },
                        opposite: true
                    },
                    @foreach($batch->get_varying_strategy_options() as $opt)
                    {
                        title: {
                            text: "{{ $opt->name }}"
                        },
                    },
                    @endforeach
                ],
                series: [{
                    name: 'Vs by hold',
                    yAxis: 0,
                    type: 'area',
                    pointWidth: 10,
                    opacity: 0.1,
                    data: {!! json_encode($batch->all_sim_runs_for_winning_strategy()->map(fn($sr) => (float) $sr->result('vs_buy_hold'))->values()) !!}
                }, 
                @foreach($batch->get_varying_strategy_options()->values() as $k => $opt)
                {
                    name: "{{ $opt->name }}",
                    yAxis: {{ $k + 1 }},
                    data: {!! $batch->all_sim_runs_for_winning_strategy()->map(fn($sr) => (float) $sr->strategy_options->find($opt->id)->pivot->value)->values() !!}     
                },
                @endforeach
                
                /*
                {
                    name: 'opt 2',
                    yAxis: 1,
                    data: [5, 7, 3]                    
                }, {
                    name: 'opt 1',
                    yAxis: 2,
                    data: [2, 4, 9]
                }
                */
                ]        
            });            
        });
    </script>
</x-layout>