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
            categories: {!! json_encode($chart_sim_runs->pluck('id')->values()) !!}
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
            @foreach($chart_options as $opt)
            {
                title: {
                    text: "{{ $opt->name }}"
                },
            },
            @endforeach
        ],
        series: [
            {
                name: 'Vs buy hold',
                yAxis: 0,
                type: 'area',                    
                opacity: 0.2,
                data: {!! json_encode($chart_sim_runs->map(fn($sr) => (float) $sr->result('vs_buy_hold'))->values()) !!}
            },
            {
                name: 'Profit',
                yAxis: 0,
                type: 'area',                    
                opacity: 0.2,
                data: {!! json_encode($chart_sim_runs->map(fn($sr) => (float) $sr->result('profit')*100)->values()) !!}
            },
            {
                name: 'Qty trades',
                yAxis: 1,
                type: 'column',
                opacity: 0.5,
                data: {!! json_encode($chart_sim_runs->map(fn($sr) => (int) $sr->result('total_trades'))->values()) !!}
            }, 
            @foreach($chart_options->values() as $k => $opt)
            {
                name: "{{ $opt->name }}",
                yAxis: {{ $k + 2 }},
                data: {!! $sim_runs_container->option_values($opt) !!}     
            },
            @endforeach
        ]        
    });     
</script>
