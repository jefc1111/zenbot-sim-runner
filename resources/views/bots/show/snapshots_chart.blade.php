<div id="bot-snpshots-chart" style="width:100%; height:400px;"></div>
<button id="fullscreen-chart-button">Fullscreen</button>
<script>
    const chart = Highcharts.chart('bot-snpshots-chart', {
        chart: {
            type: 'line',
            zoomType: 'x'
        },
        credits: {
            enabled: false
        },
        title: null,
        tooltip: {
            shared: true
        },
        xAxis: {
            categories: {!! json_encode($bot->snapshots->pluck('id')->values()) !!}
        },        
        yAxis: [                    
            {
                title: {
                    text: 'Profit'
                }
            },
            /*
            {
                title: {
                    text: 'Buy & hold profit'
                }                
            },
            {
                title: {
                    text: 'Vs. buy & hold'
                }                
            },
            */
            {
                title: {
                    text: 'Asset amt.'
                }
            },
            {
                title: {
                    text: 'Currency amt.'
                }                
            },
            {
                title: {
                    text: 'Qty trades'
                }                
            }
        ],
        navigation: {
            buttonOptions: {
                enabled: false
            }
        },
        series: [
            {
                name: 'Qty trades',
                yAxis: 3,
                type: 'area',
                opacity: 0.2,
                data: {!! json_encode($bot->snapshots->map(fn($s) => $s->qty_trades)) !!},
                marker: {
                    radius: 0
                },
            },
            {
                name: 'Asset amt.',
                yAxis: 1,
                type: 'column',
                opacity: 0.5,
                data: {!! json_encode($bot->snapshots->map(fn($s) => (float) $s->asset_amount)) !!},
                marker: {
                    radius: 0
                },
            },
            {
                name: 'Currency amt.',
                yAxis: 2,
                type: 'column',
                opacity: 0.5,
                data: {!! json_encode($bot->snapshots->map(fn($s) => (float) $s->currency_amount)) !!},
                marker: {
                    radius: 0
                },
            },
            {
                name: 'Profit',
                yAxis: 0,    
                type: 'line',
                data: {!! json_encode($bot->snapshots->map(fn($s) => (float) $s->profit)) !!},
                marker: {
                    radius: 0
                },
            },
            {
                name: 'Buy & hold profit',
                yAxis: 0,
                type: 'line',
                data: {!! json_encode($bot->snapshots->map(fn($s) => (float) $s->buy_hold_profit)) !!},
                marker: {
                    radius: 0
                },
            },
            {
                name: 'Vs. buy & hold',
                yAxis: 0,
                type: 'line',
                data: {!! json_encode($bot->snapshots->map(fn($s) => (float) ($s->profit - $s->buy_hold_profit))) !!},
                marker: {
                    radius: 0
                },
            }
        ]        
    });

    document.getElementById('fullscreen-chart-button').addEventListener('click', function () {
        chart.fullscreen.toggle();
    });
</script>
