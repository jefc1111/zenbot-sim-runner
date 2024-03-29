<x-layout>
    <h2>Strategy: {{ $strategy->name }}</h2>
    <p>Description: {{ $strategy->description }}</p>
    <div class="">
        <ul id="sim-run-batch-tab-header" class="nav nav-tabs" role="tablist">
            @include('shared.tab_header_item', [
                'active' => true, 
                'id' => 'overview', 
                'label' => 'Overview'
            ])
            @include('shared.tab_header_item', [ 
                'id' => 'sim-runs', 
                'label' => 'Sim runs ('.$strategy->sim_runs->count().')'
            ])
            @include('shared.tab_header_item', [ 
                'id' => 'analysis', 
                'label' => 'Analysis ('.$chart_sim_runs->count().')'
            ])
        </ul>
    </div>
    <br>
    <div class="">
        <div class="tab-content" id="sim-run-batch-tab-content">
            <div class="tab-pane show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                <h4>Strategy options</h4>
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Default</th>
                            <th>Unit</th>
                            <th>Step</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($strategy->options as $strategy_option)
                        <tr>
                            <td>{{ $strategy_option->id }}</td>
                            <td>{{ $strategy_option->name }}</td>
                            <td>{{ $strategy_option->description }}</td>
                            <td>{{ $strategy_option->default }}</td>
                            <td>{{ $strategy_option->unit }}</td>
                            <td>{{ $strategy_option->step }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>     
            </div>
            <div class="tab-pane" id="sim-runs" role="tabpanel" aria-labelledby="sim-runs-tab">
                <div class="row">
                    <div class="col-md-12">
                        @include('strategies.show.sim_runs_table')
                        <hr>
                        <br>
                        <p>
                            Light grey text means it is the default value for the option.
                        </p>
                        <p>
                            Light grey cell background means there is a pivot entry between the sim run and the strategy option.
                        </p>                    
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="analysis" role="tabpanel" aria-labelledby="analysis-tab">
                <div class="row">
                    <p>
                        Showing all complete {{ $strategy->name }} sim runs with quantity of trades > 0.
            <       </p>
                    <div class="col-md-12">
                        @include('sim_run_batches.show.analysis_chart', [                            
                            'show_axes_for_options' => false
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        /* ----- Duplicated for sim runs and sim run batches too ------ */
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