<x-layout>
    <table class="table">
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
</x-layout>