<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        @foreach($strategies as $strategy)
        <tr>
            <td>{{ $strategy->name }}</td>
            <td>{{ $strategy->description }}</td>
        </tr>
        @endforeach
    </tbody>
</table>