<x-layout>
    <h2>All strategies</h2>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>id</th>
                <th>Name</th>
                <th>Description</th>
                <th>Enabled</th>
            </tr>
        </thead>
        <tbody>
            @foreach($strategies as $strategy)
            <tr>
                <td>{{ $strategy->id }}</id>
                <td>
                    <a href="/strategies/{{ $strategy->id }}">{{ $strategy->name }}</a>
                </td>
                <td>{{ $strategy->description }}</td>                
                <td>{{ $strategy->enabled ? 'true' : 'false' }}</id>
            </tr>
            @endforeach
        </tbody>
    </table>    
</x-layout>