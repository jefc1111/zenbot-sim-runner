<x-layout>
    <h2>Exchange: {{ $exchange->name }}</h2>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>id</th>
                <th>Name</th>
                <th>Asset</th>
                <th>Currency</th>
                <th>Min size</th>
                <th>Max size</th>
                <th>Min total</th>
                <th>Increment</th>
                <th>Asset increment</th>
                <th>Label</th>
            </tr>
        </thead>
        <h4>Products</h4>
        <tbody>
            @foreach($exchange->products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->asset }}</td>
                <td>{{ $product->currency }}</td>
                <td>{{ $product->min_size }}</td>
                <td>{{ $product->max_size }}</td>
                <td>{{ $product->min_total }}</td>
                <td>{{ $product->increment }}</td>
                <td>{{ $product->asset_increment }}</td>
                <td>{{ $product->label }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</x-layout>