<div id="varying-options-chart" style="width:100%; height:400px;"></div>
<button id="fullscreen-chart-button">Fullscreen</button>
<script>
    const chart = Highcharts.chart('varying-options-chart', {
        chart: {
            type: 'line'
        },
        credits: {
            enabled: false
        },
        title: null,
        tooltip: {
            shared: true
        },
        xAxis: {
            categories: {!! json_encode($chart_sim_runs->pluck('id')->values()) !!}
        },
        yAxis: [                    
            {
                title: {
                    text: 'Qty trades'
                }
            },
            {
                title: {
                    text: 'Profit / Vs buy & hold'
                }                
            },
            @if($show_axes_for_options)
            @foreach($chart_options as $opt)
            {
                title: {
                    text: "{{ $opt->name }}"
                },
                opposite: true
            },
            @endforeach
            @endif
        ],
        navigation: {
            buttonOptions: {
                enabled: false
            }
        },
        series: [
            {
                name: 'Vs buy hold',
                yAxis: 1,
                type: 'area',                    
                opacity: 0.2,
                data: {!! json_encode($chart_sim_runs->map(fn($sr) => (float) $sr->result('vs_buy_hold'))->values()) !!}
            },
            {
                name: 'Profit',
                yAxis: 1,
                type: 'area',                    
                opacity: 0.2,
                data: {!! json_encode($chart_sim_runs->map(fn($sr) => (float) $sr->result('profit')*100)->values()) !!}
            },
            {
                name: 'Qty trades',
                yAxis: 0,
                type: 'column',
                opacity: 0.5,
                data: {!! json_encode($chart_sim_runs->map(fn($sr) => (int) $sr->result('total_trades'))->values()) !!}
            }, 
            @foreach($chart_options->values() as $k => $opt)
            {
                name: "{{ $opt->name }}",
                yAxis: {{ $show_axes_for_options ? $k + 2 : 0 }},
                data: {!! $sim_runs_container->option_values($opt) !!}     
            },
            @endforeach
        ]        
    });

    document.getElementById('fullscreen-chart-button').addEventListener('click', function () {
        chart.fullscreen.toggle();
    });
</script>
