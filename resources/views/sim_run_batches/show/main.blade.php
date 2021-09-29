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
            @include('shared.tab_header_item', [
                'active' => true, 
                'id' => 'overview', 
                'label' => 'Overview'
            ])
            @include('shared.tab_header_item', [ 
                'id' => 'sim-runs', 
                'label' => 'Sim runs ('.$batch->sim_runs->count().')'
            ])
            @include('shared.tab_header_item', [
                'id' => 'analysis', 
                'label' => 'Analysis'
            ])
            @include('shared.tab_header_item', [
                'id' => 'family-tree', 
                'label' => 'Family tree ('.$batch->batch_ancestry_and_descendants()->count().')'
            ])
            @include('shared.tab_header_item', [
                'id' => 'batch-admin', 
                'label' => 'Batch admin'
            ])
            @include('shared.tab_header_item', [
                'id' => 'backfill', 
                'label' => 'Backfill log'
            ])
        </ul>
    </div>
    <br>
    <div class="">
        <div class="tab-content" id="sim-run-batch-tab-content">
            <div class="tab-pane show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                <div class="row">
                    @include('sim_run_batches.metadata_snippet')
                </div>      
                <div class="row">
                    <button {{ Auth::user()->has_sim_time() ? null : 'disabled' }} style="margin: 3px; " type="button" class="btn btn-block btn-success col-md-3" id="run">
                        Initiate batch <ion-icon name="play"></ion-icon>
                    </button>                    
                    @include('shared.no_sim_time_warning')                    
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
            <div class="tab-pane" id="batch-admin" role="tabpanel" aria-labelledby="batch-admin-tab">
                @include('sim_run_batches.show.admin')
            </div>
            <div class="tab-pane" id="backfill" role="tabpanel" aria-labelledby="backfill-tab">
                @include('sim_run_batches.show.backfill')
            </div>
        </div>            
    </div>
    <script>
        $("#run").click(function() {
            $.get("/sim-run-batches/run/{{ $batch->id }}", function(res) {
                alert(res.msg)          
            });
        });

        /* ----- Duplicated for sim runs too ------ */
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
        /* ---------------- */
        
        function populateBackfillLog() {
            const qtyCurrentLines = $("#backfill-log ul li").length;

            $.get("backfill-log/{{ $batch->id }}", function(rdata) {
                if (rdata.lines.length) {
                    $("#backfill-log h4").empty();
                    
                    rdata.lines.slice(qtyCurrentLines).forEach(function(line, i) {
                        $("#backfill-log ul").append(`<li>${line}</li>`);
                    });
                } else {
                    $("#backfill-log h4").text("No log found")
                }
            });
        }

        function poll() {
            if (window.location.hash === "#backfill") {
                populateBackfillLog()
            }
            
            setTimeout(poll, 1000);
        }

        setTimeout(poll, 1000);
    </script>
</x-layout>