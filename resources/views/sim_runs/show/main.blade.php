<x-layout>
    <div class="row">
        <h4>Sim run {{ $sim_run->id }}</h4>
    </div>
    <div class="row">    
        <p>
            From batch: <a href="/sim-run-batches/{{ $sim_run->sim_run_batch->id }}">{{ $sim_run->sim_run_batch->name }}</a><br>
            Strategy: <a href="/strategies/{{ $sim_run->strategy->id }}">{{ $sim_run->strategy->name }}</a><br>
        </p>
    </div>
    <div class="">
        <ul id="sim-run-tab-header" class="nav nav-tabs" role="tablist">
            @include('shared.tab_header_item', [
                'active' => true, 
                'id' => 'overview', 
                'label' => 'Overview'
            ])
            @include('shared.tab_header_item', [               
                'id' => 'options', 
                'label' => 'Strategy options'
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
            <div class="tab-pane show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                @include('sim_runs.show.overview')
            </div>
            <div class="tab-pane" id="options" role="tabpanel" aria-labelledby="options-tab">
                @include('sim_runs.show.options')
            </div>
            <div class="tab-pane" id="log" role="tabpanel" aria-labelledby="log-tab">
                @include('sim_runs.show.log')
            </div>
            <div class="tab-pane show" id="result" role="tabpanel" aria-labelledby="result-tab">
                @include('sim_runs.show.result')
            </div>
        </div>
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
        /* --------- */ 
    </script>
</x-layout>