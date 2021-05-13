<x-layout>
    <div class="row">
        <div class="col-md-6">
            <h3>
                {{ $batch->name }}
            </h3>                
        </div>
        <div style="text-align: right; " class="text-muted col-md-6">
            <h5>
                {{ $batch->get_pair_name() }} {{ $batch->humanised_date_range() }}
            </h5>
        </div>
        <div class="col-md-6">
            
        </div>
        <div style="text-align: right; " class="text-muted col-md-6">
            <small><span class="{{ $batch->percent_complete() === 100 ? 'text-success' : null }}">{{ $batch->percent_complete() }}% complete</span> ({{ $batch->qty_errored() }} errored)</small>
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
            <div class="tab-pane show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                <div class="row">
                    @include('sim_run_batches.metadata_snippet')
                </div>      
                <div class="row">
                    <button style="margin: 3px; " type="button" class="btn btn-block btn-success col-md-2" id="run">Run</button>    
                </div>          
                <div class="row">
                    <a style="margin: 3px; " type="button" class="btn btn-block btn-primary col-md-2" href="copy/{{ $batch->id }}">Copy</a>
                    <span class="text-muted">Copy basic batch info only. Strategies can be selected and refined after copying.</class>
                </div>
            </div>
            <div class="tab-pane" id="sim-runs" role="tabpanel" aria-labelledby="sim-runs-tab">
                <div class="row">
                    <div class="col-md-12">
                        @include('sim_run_batches.show.sim_runs_table')  
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="analysis" role="tabpanel" aria-labelledby="analysis-tab">
                @include('sim_run_batches.show.analysis_chart', ['strategy' => $batch->winning_strategy()])
                @include('sim_run_batches.show.analysis_text', ['strategy' => $batch->winning_strategy()])
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
    </script>
</x-layout>