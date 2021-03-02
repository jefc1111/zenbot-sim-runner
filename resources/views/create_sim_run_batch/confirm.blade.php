<x-layout>
    <h2>Confirm sim runs for batch</h2>
    <form method="post" action="/sim-run-batch">
        @csrf  
        <table>
            <thead>
                <tr>
                    <th>Strategy</th>
                    <th>Options</th>                    
                    <th>Include</th>
                </tr>
            </thead>
            <tbody>
                @foreach([] as $strategy)
                <tr>
                    <td>{{ $strategy->id }}</id>
                    <td>{{ $strategy->name }}</td>
                    <td>
                        <input 
                            type="checkbox" 
                            id="strategy-{{ $strategy->id }}" 
                            name="strategies[]" 
                            value="{{ $strategy->id }}"
                        >            
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <input type="submit" value="Submit">
    </form>    
</x-layout>