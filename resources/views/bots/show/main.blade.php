<x-layout>
    <div class="row">
        <div class="col-md-6">
            <h3>Bot {{ $bot->name }}</h3>            
        </div>
        <div style="text-align: right; " class="text-muted col-md-6">
            <p>
                <a href="/bots">List bots</a>
            </p>
        </div>
    </div>
    <div class="">
        <ul id="bot-tab-header" class="nav nav-tabs" role="tablist">
            @include('shared.tab_header_item', [               
                'id' => 'summary', 
                'label' => 'Summary',
                'active' => true
            ])
            @include('shared.tab_header_item', [
                'id' => 'snapshots-table', 
                'label' => 'Snapshots (table)'
            ])
            @include('shared.tab_header_item', [
                'id' => 'snapshots-chart', 
                'label' => 'Snapshots (chart)'
            ])
        </ul>
    </div>
    <br>
    <div>
        <div class="tab-content" id="bot-tab-content">
            <div class="tab-pane active" id="summary" role="tabpanel" aria-labelledby="summary-tab">
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <td>Name</td>
                            <td>{{ $bot->name }}</td>
                        </tr>
                        <tr>
                            <td>Args</td>
                            <td>{{ $bot->args }}</td>
                        </tr>
                        <tr>
                            <td>Discord username</td>
                            <td>{{ $bot->discord_username }}</td>
                        </tr>
                        <tr>
                            <td>Zenbot port</td>
                            <td>{{ $bot->zenbot_port }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane show" id="snapshots-table" role="tabpanel" aria-labelledby="snapshots-table-tab">
                @include('bots.show.snapshots_table')
            </div>
            <div class="tab-pane" id="snapshots-chart" role="tabpanel" aria-labelledby="snapshots-chart-tab">
                @include('bots.show.snapshots_chart')
            </div>
        </div>
    </div>

    <script>
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
        /* ---------------- */
    </script>
</x-layout>