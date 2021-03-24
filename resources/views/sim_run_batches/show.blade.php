<x-layout>
    <h2>Sim run batch {{ $batch->id }}</h2>
    {{ $batch->name }}<br>
    {{ $batch->info }}<br>
    {{ $batch->product->name }}<br>
    {{ $batch->exchange->name }}<br>
    {{ $batch->start }}<br>
    {{ $batch->end }}<br>
    {{ $batch->buy_pct }}<br>
    {{ $batch->sell_pct }}<br>
    <h3>Sim runs</h3>
    <table>
        <thead>
            <tr>
                <th>id</th>
                <th>Strategy</th>                
                <th>vs. buy hold</th>
            </tr>
        </thead>
        <tbody>
            @foreach($batch->sim_runs as $sim_run)
            <tr>
                <td>
                    <a href="/sim-runs/{{ $sim_run->id }}">{{ $sim_run->id }}</a>
                </id>
                <td>
                    <a href="/strategies/{{ $sim_run->strategy->id }}">{{ $sim_run->strategy->name }}</a>
                </td>
                <td>
                    @if($sim_run->result)
                    {{ $sim_run->vs_buy_hold }}
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>    
    <span id="run">RUN</span>
    <script>
        $("#run").click(function() {
            $.get("/sim-run-batches/run/{{ $batch->id }}", function(res) {
                if (! res.success) {
                    alert(res.error);
                } else {
                    location.reload();
                }                
            });
        });
    </script>
</x-layout>