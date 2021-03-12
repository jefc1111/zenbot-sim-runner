<x-layout>
    <h2>All strategies</h2>
    <table>
        <thead>
            <tr>
                <th>id</th>
                <th>Name</th>
                <th>Description</th>
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
            </tr>
            @endforeach
        </tbody>
    </table>    
</x-layout>