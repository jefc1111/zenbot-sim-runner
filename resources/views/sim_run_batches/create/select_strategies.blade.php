<x-layout>
    <h2>Select strategies</h2>
    <form method="post" action="/sim-run-batches/create/refine-strategies">
        @csrf  
        <table>
            <thead>
                <tr>
                    <th>id</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Include</th>
                </tr>
            </thead>
            <tbody>
                @foreach($strategies as $strategy)
                <tr>
                    <td>{{ $strategy->id }}</id>
                    <td>{{ $strategy->name }}</td>
                    <td>{{ $strategy->description }}</td>
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