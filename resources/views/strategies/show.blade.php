<x-layout>
    <h2>Strategy: {{ $strategy->name }}</h2>
    <p>Description: {{ $strategy->description }}</p>
    <table>
        <thead>
            <tr>
                <th>id</th>
                <th>Name</th>
                <th>Description</th>
                <th>Default</th>
                <th>Unit</th>
                <th>Step</th>
            </tr>
        </thead>
        <tbody>
            @foreach($strategy->options as $strategy_option)
            <tr>
                <td>{{ $strategy_option->id }}</td>
                <td>{{ $strategy_option->name }}</td>
                <td>{{ $strategy_option->description }}</td>
                <td>{{ $strategy_option->default }}</td>
                <td>{{ $strategy_option->unit }}</td>
                <td>{{ $strategy_option->step }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</x-layout>