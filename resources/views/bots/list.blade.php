<x-layout>
    <h2>Bots</h2>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>id</th>
                <th>Name</th>
                <th>Qty snapshots</th>
                <th>Last snapshot</th>
                @include('bots.shared.snapshot_header_cells')
                <th>Uptime</th>
                <th>Active</th>
                <th>Zenbot port</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bots as $bot)
            <tr>
                <td>{{ $bot->id }}</id>
                <td>
                    <a href="/bots/{{ $bot->id }}">{{ $bot->name }}</a>                  
                </id>
                <td>
                    {{ $bot->snapshots->count() }}
                </id>
                <td>
                    {{ $bot->latest_snapshot ? $bot->latest_snapshot->age() : null }}
                </td>
                @if($bot->latest_snapshot)
                @include('bots.shared.snapshot_body_cells')
                @else
                {!! str_repeat("<td></td>", 6) !!}
                @endif
                <td>
                    {{ $bot->snapshots->count() ? $bot->uptime() : null }}
                </td>
                <td>
                    {{ $bot->active ? 'true' : 'false' }}
                </id>
                <td>
                    <a target="blank" href="{{ config('zenbot.bot_monitoring.base_url') }}:{{ $bot->zenbot_port }}">{{ $bot->zenbot_port }}</a>
                </id>
            </tr>
            @endforeach
        </tbody>
    </table>    
</x-layout>