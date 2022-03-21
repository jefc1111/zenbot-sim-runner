<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>id</th>
            <th>Name</th>
            @include('bots.shared.snapshot_header_cells')            
            <th>Qty snapshots</th>
            <th>Last snapshot</th>            
            <th>Uptime</th>
            <th>Zenbot port</th>
            <th>Mode</th>
            <th>Active</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bots as $bot)
        <tr>
            <td class="{{ $bot->is_live() ? 'table-info' : null }}">{{ $bot->id }}</id>
            <td>
                <a href="/bots/{{ $bot->id }}">{{ $bot->name }}</a>                  
            </id>
            @if($bot->latest_snapshot)
            @include('bots.shared.snapshot_body_cells', ['snapshot' => $bot->latest_snapshot])
            @else
            {!! str_repeat("<td></td>", 6) !!}
            @endif
            <td>
                {{ $bot->snapshots->count() }}
            </id>
            <td>
                {{ $bot->latest_snapshot ? $bot->latest_snapshot->age() : null }}
            </td>
            <td>
                {{ $bot->snapshots->count() ? $bot->uptime() : null }}
            </td>
            <td>
                <a target="blank" href="{{ config('zenbot.bot_monitoring.base_url') }}:{{ $bot->zenbot_port }}">{{ $bot->zenbot_port }}</a>
            </id>
            <td>
                {{ $bot->is_paper() ? 'paper' : 'live' }}
            </id>
            <td>
                {{ $bot->active ? 'true' : 'false' }}
            </id>
        </tr>
        @endforeach
    </tbody>
</table>    