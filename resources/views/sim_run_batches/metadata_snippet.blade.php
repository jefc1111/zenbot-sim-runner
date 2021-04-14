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
        <tr>
            <td>Buy %</td>
            <td>{{ $batch->buy_pct }}</td>
        </tr>
        <tr>
            <td>Sell %</td>
            <td>{{ $batch->sell_pct }}</td>
        </tr>
    </tbody>
</table>