<div id="varying-options-chart" style="width:100%; height:400px;"></div>

<script>
    const chart = Highcharts.chart('varying-options-chart', {
        chart: {
            type: 'line'
        },
        credits: {
            enabled: false
        },
        title: {
            text: 'Sim runs for strategy "{{ $strategy->name }}"'
        },
        tooltip: {
            shared: true
        },
        xAxis: {
            categories: {!! json_encode($batch->all_sim_runs_for_strategy($strategy, 'vs_buy_hold')->pluck('id')->values()) !!}
        },
        yAxis: [                    
            {
                title: {
                    text: 'Profit / Vs buy & hold'
                },
                opposite: true
            },
            {
                title: {
                    text: 'Qty trades'
                },
                opposite: true
            },
            @foreach($batch->get_varying_options_for_winning_strategy() as $opt)
            {
                title: {
                    text: "{{ $opt->name }}"
                },
            },
            @endforeach
        ],
        series: [{
            name: 'Profit',
            yAxis: 0,
            type: 'area',                    
            opacity: 0.2,
            data: {!! json_encode($batch->all_sim_runs_for_strategy($strategy, 'vs_buy_hold')->map(fn($sr) => (float) $sr->result('profit')*100)->values()) !!}
        },
        {
            name: 'Vs buy hold',
            yAxis: 0,
            type: 'area',                    
            opacity: 0.2,
            data: {!! json_encode($batch->all_sim_runs_for_strategy($strategy, 'vs_buy_hold')->map(fn($sr) => (float) $sr->result('vs_buy_hold'))->values()) !!}
        },
        {
            name: 'Qty trades',
            yAxis: 1,
            type: 'column',
            opacity: 0.5,
            data: {!! json_encode($batch->all_sim_runs_for_strategy($strategy, 'vs_buy_hold')->map(fn($sr) => (int) $sr->result('total_trades'))->values()) !!}
        }, 
        @foreach($batch->get_varying_options_for_winning_strategy()->values() as $k => $opt)
        {
            name: "{{ $opt->name }}",
            yAxis: {{ $k + 2 }},
            data: {!! $batch->option_values($opt) !!}     
        },
        @endforeach
        ]        
    });     
</script>
