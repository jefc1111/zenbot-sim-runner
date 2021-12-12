<x-layout>
    <h2>Exchanges</h2>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>id</th>
                <th>Name</th>
                <th>Enabled</th>
            </tr>
        </thead>
        <tbody>
            @foreach($exchanges as $exchange)
            <tr>
                <td>{{ $exchange->id }}</id>
                <td>
                    <a href="/exchanges/{{ $exchange->id }}">{{ $exchange->name }}</a>
                </td>
                <td>{{ $exchange->enabled ? 'true' : 'false' }}</id>
            </tr>
            @endforeach
        </tbody>
    </table>    
</x-layout>