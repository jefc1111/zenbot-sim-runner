<x-layout>
    <h2>Bots</h2>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>id</th>
                <th>Name</th>
                <th>Qty snapshots</th>
                <th>Last snapshot</th>                
                <th>Profit</th>
                <th>HODL</th>
                <th>Vs HODL</th>
                <th>Asset amt</th>
                <th>Currency amt</th>
                <th>Uptime</th>
                <th>Active</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bots as $bot)
            <tr>
                <td>{{ $bot->id }}</id>
                <td>
                    <a href="/bots/{{ $bot->id }}">{{ $bot->name }}</a>                    
                </id>
                <td>{{ $bot->snapshots->count() }}</id>
                <td>{{ $bot->latest_snapshot ? $bot->latest_snapshot->age() : null }}</td>
                <td class="text-success">{{ $bot->latest_snapshot ? $bot->latest_snapshot->profit.'%' : null }}</td>
                <td>{{ $bot->latest_snapshot ? $bot->latest_snapshot->buy_hold_profit.'%' : null }}</td>
                <td class="text-success">{{ $bot->latest_snapshot ? $bot->latest_snapshot->vs_buy_hold.'%' : null }}</td>
                <td>{{ $bot->latest_snapshot ? $bot->latest_snapshot->asset_amount : null }}</td>
                <td>{{ $bot->latest_snapshot ? $bot->latest_snapshot->currency_amount : null }}</td>
                <td>{{ $bot->snapshots->count() ? $bot->uptime() : null }}</td>
                <td>{{ $bot->active ?  'true' : 'false' }}</id>
            </tr>
            @endforeach
        </tbody>
    </table>    
</x-layout>