<x-layout>
    <h2>Bots</h2>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>id</th>
                <th>Name</th>
                <th>Last snapshot</th>                
                <th>Profit</th>
                <th>HODL</th>
                <th>Vs HODL</th>
                <th>Asset (BTC)</th>
                <th>Currency (BUSD)</th>
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
                <td>10 MINUTES AGO</td>
                <td class="text-success">87%</td>
                <td>8%</td>
                <td class="text-success">198%</td>
                <td>0.00021</td>
                <td>1254</td>
                <td>3 days</td>
                <td>{{ $bot->active ?  'true' : 'false' }}</id>
            </tr>
            @endforeach
        </tbody>
    </table>    
</x-layout>