<x-layout>
    <table class="table">
        <thead>
            <th>Cost</th>
            <th>Sim time</th>
            <th>Discount</th>
            <th></th>
        </thead>
        <tbody>
            @foreach ($sim_time_bundles as $bundle)
            <tr>
                <td>${{ $bundle->cost }}</td>
                <td>{{ $bundle->qty_hours }} hours</td>
                <td>{{ $bundle->get_discount() }}%</td>
                <td>
                    <span class="btn btn-primary btn-md">
                        Buy {{ $bundle->qty_hours }} hours of sim time for ${{ $bundle->cost }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
        </tfoot>
    </table>
</x-layout>