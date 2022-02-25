<x-layout>
    <h2>Bots</h2>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>id</th>
                <th>Name</th>
                <th>Qty snapshots</th>
                <th>Last snapshot</th>
                <th>Qty trades</th>                
                <th>Profit</th>
                <th>HODL</th>
                <th>Vs HODL</th>
                <th>Asset amt</th>
                <th>Currency amt</th>
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
                <td>
                    {{ $bot->latest_snapshot ? $bot->latest_snapshot->qty_trades : null }}
                </td>
                @include('table_components.good_bad_cell', [
                    'content' => $bot->latest_snapshot ? $bot->latest_snapshot->profit.'%' : null,
                    'value' => $bot->latest_snapshot ? $bot->latest_snapshot->profit : null                    
                ])
                @include('table_components.good_bad_cell', [
                    'content' => $bot->latest_snapshot ? $bot->latest_snapshot->buy_hold_profit.'%' : null,
                    'value' => $bot->latest_snapshot ? $bot->latest_snapshot->buy_hold_profit : null                    
                ])
                @include('table_components.good_bad_cell', [
                    'content' => $bot->latest_snapshot ? ($bot->latest_snapshot->profit - $bot->latest_snapshot->buy_hold_profit).'%' : null,
                    'value' => $bot->latest_snapshot ? ($bot->latest_snapshot->profit - $bot->latest_snapshot->buy_hold_profit) : null                    
                ])
                <td>
                    {{ $bot->latest_snapshot ? number_format($bot->latest_snapshot->asset_amount, 8) : null }}
                </td>
                <td>
                    {{ $bot->latest_snapshot ? number_format($bot->latest_snapshot->currency_amount, 8) : null }}
                </td>
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