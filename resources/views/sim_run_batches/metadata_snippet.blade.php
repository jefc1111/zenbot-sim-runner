<table class="table table-sm">
    <thead>
    </thead>
    <tbody>
        <tr>
            <td>Name</td>
            <td>{{ $batch->name }}</td>
        </tr>
        <tr>
            <td>Info</td>
            <td>{{ $batch->info }}</td>
        </tr>
        <tr>
            <td>Product</td>
            <td>{{ $batch->product->name }}</td>
        </tr>
        <tr>
            <td>Exchange</td>
            <td>{{ $batch->exchange->name }}</td>
        </tr>
        <tr>
            <td>Start date</td>
            <td>{{ $batch->start }}</td>
        </tr>
        <tr>
            <td>End date</td>
            <td>{{ $batch->end }}</td>
        </tr>
        @if(! ($compact ?? false))
        <tr>
            <td>Buy %</td>
            <td>{{ $batch->buy_pct }}</td>
        </tr>
        <tr>
            <td>Sell %</td>
            <td>{{ $batch->sell_pct }}</td>
        </tr>
        <tr>
            <td>Autospawn children</td>
            <td>{{ $batch->allow_autospawn ? 'yes' : 'no' }}</td>
        </tr>
        <tr>
            <td>Backfill run time</td>
            <td>{{ $batch->backfill_runtime }}s. ({{ gmdate("H\h i\m s\s", $batch->backfill_runtime) }})</td>
        </tr>
        <tr>
            <td>Run time (aggregate)</td>
            <td>{{ $batch->run_time() }}s. ({{ gmdate("H\h i\m s\s", $batch->run_time()) }})</td>
        </tr>
        <tr>
            <td>Owner</td>
            <td>{{ $batch->user->email }}</td>
        </tr>
        @endif
    </tbody>
</table>