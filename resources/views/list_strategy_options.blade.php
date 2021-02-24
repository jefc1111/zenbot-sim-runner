<x-layout>
    <h2>All strategy options</h2>
    <table>
        <thead>
            <tr>
                <th>id</th>
                <th>Strategy</th>
                <th>Name</th>                
                <th>Description</th>
                <th>Default</th>
                <th>Unit</th>
                <th>Step</th>
            </tr>
        </thead>
        <tbody>
            @foreach($strategy_options as $strategy_option)
            <tr>
                <td>{{ $strategy_option->id }}</td>
                <td>
                    <a href="/strategies/{{ $strategy_option->strategy->id }}">
                        {{ $strategy_option->strategy->name }}
                    </a>
                </td>
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