<x-layout>
    <h4>Available bundles</h4>
    <table class="table table-dark">
        <thead>
            <th>Cost</th>
            <th>Sim time</th>
            <th>Cost per hour</th>
            <th>Discount</th>
            <th></th>
        </thead>
        <tbody>
            @foreach ($sim_time_bundles as $bundle)
            <tr>
                <td>{{ $bundle->currency_symbol }}{{ $bundle->cost }}</td>
                <td>{{ $bundle->qty_hours }} hours</td>
                <td>{{ $bundle->currency_symbol }}{{ round($bundle->cost_per_hour(), 4) }}</td>
                <td>{{ round($bundle->get_discount(), 2) }}%</td>
                <td>
                    <a href="/shop/buy-sim-time-bundle/{{ $bundle->id }}" class="btn btn-primary btn-md">
                        Buy {{ $bundle->qty_hours }} hours of sim time for ${{ $bundle->cost }}
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
        </tfoot>
    </table>
    <h4>Previous orders</h4>
    <table class="table table-sm table-bordered">
        <thead>
            <th>Order number</th>
            <th>Invoice id</th>
            <th>Cost</th>
            <th>Status</th>
            <th>Date / time</th>
        </thead>
        <tbody>
            @foreach ($sim_time_orders->reverse() as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->invoice_id }}</td>
                <td>{{ $order->sim_time_bundle->currency_symbol }}{{ $order->sim_time_bundle->cost }}</td>
                <td>{{ $order->status }}</td>
                <td>{{ $order->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
        </tfoot>
    </table>
</x-layout>