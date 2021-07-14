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
            <small>
                status: <span class="text-{{ $batch->statuses[$batch->status]['style'] }}">{{ $batch->status }}</span> 
                @if(! ($batch->status === 'ready' || $batch->status === 'backfilling'))
                |
                <span class="{{ $batch->percent_complete() === 100 ? 'text-success' : null }}">                    
                    {{ $batch->percent_complete() }}% complete
                </span> ({{ $batch->qty_errored() }} errored)
                @endif
            </small>
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
                <a id="analysis-tab" data-toggle="tab" class="nav-link" href="#analysis">
                    Analysis
                </a>
            </li>
            <li class="nav-item">
                <a id="family-tree-tab" data-toggle="tab" class="nav-link" href="#family-tree">Family tree ({{ $batch->batch_ancestry_and_descendants()->count() }})</a>
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
                    <button {{ Auth::user()->has_sim_time() ? null : 'disabled' }} style="margin: 3px; " type="button" class="btn btn-block btn-success col-md-2" id="run">
                        Initiate batch
                    </button>                    
                    @include('shared.no_sim_time_warning')                    
                </div>          
                <div class="row">
                    <a style="margin: 3px; " type="button" class="btn btn-block btn-primary col-md-2" href="copy/{{ $batch->id }}">Copy</a>
                    <span style="padding: 16px 0 0 4px; " class="text-muted">
                        Copy basic batch info only. Strategies can be selected and refined after copying.
                    </class>
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
                @if(! $batch->sim_runs->isEmpty() && $batch->is_complete())
                @include('sim_run_batches.show.analysis_chart', ['strategy' => $batch->winning_strategy()])
                @include('sim_run_batches.show.analysis_text', ['strategy' => $batch->winning_strategy()])
                @else
                <br>
                <p>
                    Analysis will be available when the batch is complete
                </p>
                @endif
            </div>
            <div class="tab-pane" id="family-tree" role="tabpanel" aria-labelledby="family-tree-tab">
                @include('sim_run_batches.show.family_tree', [
                    'batches' => $batch->batch_ancestry_and_descendants(),
                    'subject_batch' => $batch,
                ])
            </div>
        </div>            
    </div>
    <script>
        $("#run").click(function() {
            $.get("/sim-run-batches/run/{{ $batch->id }}", function(res) {
                alert(res.msg)          
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