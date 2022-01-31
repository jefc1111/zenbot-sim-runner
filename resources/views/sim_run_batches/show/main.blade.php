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
        <div style="text-align: right" class="text-muted col-md-6">
            <small>
                status: <span id="batch-status" class="text-{{ $batch->get_status_data($batch->status, 'style') }}">
                    {{ $batch->status }}
                </span>
                <span id="progress-indicator">
                    |
                    <span class="{{ $batch->percent_complete() === 100 ? 'text-success' : null }}">                    
                        <span id="percent-complete">{{ $batch->percent_complete() }}</span>% complete
                    </span> (<span id="qty-errored">{{ $batch->qty_errored() }}</span> errored)
                </span>     
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
                @include('shared.live_log')
                @if (Auth::user()->hasRole('admin'))
                <p>
                    {{ $batch->get_backfill_cmd() }}
                </p>
                @endif
            </div>
        </div>
        <div class="row">
            <button {{ Auth::user()->has_sim_time() && $batch->status === 'ready' ? null : 'disabled' }} style="margin: 3px; " type="button" class="btn btn-block btn-success col-md-3" id="run">
                Initiate batch <ion-icon name="play"></ion-icon>
            </button>
            <button {{ Auth::user()->has_sim_time() && $batch->can_be_cancelled() ? null : 'disabled' }} style="margin: 3px; " type="button" class="btn btn-block btn-primary col-md-3" id="cancel">
                Cancel <ion-icon name="stop-circle-outline"></ion-icon>
            </button>                    
            @include('shared.no_sim_time_warning')                    
        </div>            
    </div>



    <div class="toast" style="position: fixed; top: 20px; right: 20px;" data-delay="3000">
        <div class="toast-header">
            <svg 
                class="rounded mr-2" 
                width="20" 
                height="20" 
                xmlns="http://www.w3.org/2000/svg" 
                preserveAspectRatio="xMidYMid slice"
                focusable="false" 
                role="img"
            >
            <rect fill="#007aff" width="100%" height="100%" /></svg>
            <strong class="mr-auto">Batch <span class="toast-content">submitted<span></strong>
            <small></small>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body">
            {{ $batch->name }} <span class="toast-content">submitted<span>
        </div>
    </div>


    <script>
        function toast(str, colour) {
            $(".toast-content").text(str);

            $("div.toast").find("rect").attr("fill", colour);

            $(".toast").toast('show');
        } 

        $.fn.removeClassStartingWith = function (filter) {
            $(this).removeClass(function (index, className) {
                return (className.match(new RegExp("\\S*" + filter + "\\S*", 'g')) || []).join(' ')
            });
            return this;
        };

        var allStatuses = {!! json_encode($batch->all_statuses()) !!};

        $("#run").click(function() {
            var that = this

            $.get("/sim-run-batches/run/{{ $batch->id }}", function(res) {
                toast("submitted", $(that).css("background-color"));
                
                $("#run").attr("disabled", true);

                $("#cancel").attr("disabled", false);
            });
        });

        $("#cancel").click(function() {
            var that = this

            if (confirm("Are you sure?")) {
                $.get("/sim-run-batches/cancel/{{ $batch->id }}", function(res) {
                    toast("cancelled", $(that).css("background-color"));                    
                });
            }
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
        
        function populateLiveLog() {
            const qtyCurrentLines = $("#live-log ul li").length;

            $.get("backfill-log/{{ $batch->id }}", function(rdata) {
                if (rdata.lines.length) {
                    $("#live-log h4").empty();
                    
                    rdata.lines.slice(qtyCurrentLines).forEach(function(line, i) {
                        $("#live-log ul").append(`<li>${line}</li>`);
                    });
                } else {
                    $("#live-log h4").text("No log found")
                }
            });
        }
         
        function updateStatus(el, statusKey, force) {
            if (force || ! el.text().includes(statusKey)) {                
                el
                .html(statusKey)
                .removeClassStartingWith("text-")
                .addClass(`text-${allStatuses[statusKey].style}`);
                
                if (allStatuses[statusKey].spinner) {
                    el.append('<div class="animated-ellipsis">');
                } else {
                    el.removeClass("animated-ellipsis");
                }
            }
        }

        function populateStatus(force) {
            $.get("status/{{ $batch->id }}", function(rdata) {                
                updateStatus($("span#batch-status"), rdata.batch_status, force)
            
                rdata.sim_run_statuses.map(function(sr) {
                    updateStatus($("span.sim-run-status[data-id=" + sr.id + "]"), sr.status, force)                        
                });

                if ($("span#percent-complete").text() != rdata.percent_complete) {
                    $("span#percent-complete").text(rdata.percent_complete);
                }   

                if ($("span#qty-errored").text() != rdata.qty_errored) {
                    $("span#qty-errored").text(rdata.qty_errored);
                }            
            });
        }        

        function poll(count) {            
            if (window.location.hash === "#backfill") {
                populateLiveLog()
            }

            populateStatus(count === 0)

            setTimeout(function() { poll(count + 1); }, 1000);
        }

        setTimeout(function() { poll(0); }, 1000);
    </script>

    <style>
        /* https://dev.to/afif/i-made-100-css-loaders-for-your-next-project-4eje */
        .animated-ellipsis {
            margin: 0 4px;
            display: inline-block;
            width:24px;
            height:6px;
            background: 
                radial-gradient(circle closest-side,currentColor 50%,#0000) 0%   50%,
                radial-gradient(circle closest-side,currentColor 50%,#0000) 50%  50%,
                radial-gradient(circle closest-side,currentColor 50%,#0000) 100% 50%;
            background-size:calc(100%/3) 100%;
            background-repeat: no-repeat;
            animation:d7 1s infinite linear;
        }
        @keyframes d7 {
            33%{background-size:calc(100%/3) 0%  ,calc(100%/3) 100%,calc(100%/3) 100%}
            50%{background-size:calc(100%/3) 100%,calc(100%/3) 0%  ,calc(100%/3) 100%}
            66%{background-size:calc(100%/3) 100%,calc(100%/3) 100%,calc(100%/3) 0%  }
        }
    </style>
</x-layout>