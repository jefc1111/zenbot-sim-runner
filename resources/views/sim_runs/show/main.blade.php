<x-layout>
    <div class="row">
        <div class="col-md-6">
            <h3>
                Sim run {{ $sim_run->id }}
            </h3>              
        </div>
        <div style="text-align: right; " class="text-muted col-md-6">
            <p>
                From batch: <a href="/sim-run-batches/{{ $sim_run->sim_run_batch->id }}">{{ $sim_run->sim_run_batch->name }}</a><br>
                Strategy: <a href="/strategies/{{ $sim_run->strategy->id }}">{{ $sim_run->strategy->name }}</a><br>
                @if($has_zenbot_html_output)
                    <a target="_blank" href="{{ url('sim-runs/zenbot-html-output', $sim_run->id) }}">Zenbot chart output</a><br>
                @endif
            </p>
        </div>
    </div>
    <div class="">
        <ul id="sim-run-tab-header" class="nav nav-tabs" role="tablist">
            @include('shared.tab_header_item', [               
                'id' => 'options', 
                'label' => 'Strategy options',
                'active' => true
            ])
            @include('shared.tab_header_item', [
                'id' => 'zenbot-commands', 
                'label' => 'Zenbot commands'
            ])
            @include('shared.tab_header_item', [ 
                'id' => 'log', 
                'label' => 'Log'
            ])
            @include('shared.tab_header_item', [
                'id' => 'result', 
                'label' => 'Result'
            ])
        </ul>
    </div>
    <br>
    <div>
        <div class="tab-content" id="sim-run-tab-content">
            <div class="tab-pane active" id="options" role="tabpanel" aria-labelledby="options-tab">
                @include('sim_runs.show.options')
            </div>
            <div class="tab-pane show" id="zenbot-commands" role="tabpanel" aria-labelledby="zenbot-commands-tab">
                @include('sim_runs.show.zenbot_commands')
            </div>
            <div class="tab-pane" id="log" role="tabpanel" aria-labelledby="log-tab">
                @include('shared.live_log')
            </div>
            <div class="tab-pane show" id="result" role="tabpanel" aria-labelledby="result-tab">
                @include('sim_runs.show.result')
            </div>
        </div>
        <button {{ Auth::user()->has_sim_time() ? null : 'disabled' }} type="button" class="btn btn-success" id="run">
            Initiate sim run <ion-icon name="play"></ion-icon>
        </button>
    </div>
    
    <script>
        $("#run").click(function() {
            $.get("/sim-runs/run/{{ $sim_run->id }}", function(res) {
                alert(res.msg)
            });
        });

        /* ----- Duplicated for batches too ------ */
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var hash = $(e.target).attr('href');
            if (history.pushState) {
                history.pushState(null, null, hash);
            } else {
                location.hash = hash;
            }
        });

        var hash = window.location.hash;
        
        if (hash) {
            $('.nav-link[href="' + hash + '"]').tab('show');
        }       

        function populateLiveLog() {
            $.get("log/{{ $sim_run->id }}", function(rdata) {
                if (rdata.lines.length) {
                    $("#live-log h4").empty();
                    
                    if (rdata.lines !== $("#live-log pre").html()) {
                        $("#live-log pre").html(rdata.lines);
                    }                    
                } else {
                    $("#live-log h4").text("No log found");

                    $("#live-log pre").empty();
                }
            });
        }

        function poll() {
            if (window.location.hash === "#log") {
                populateLiveLog()
            }
            
            setTimeout(poll, 1000);
        }

        setTimeout(poll, 1000);
        /* ---------------- */
    </script>
</x-layout>