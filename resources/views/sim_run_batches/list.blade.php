<x-layout>
    <h2>Sim run batches</h2>
    <table>
        <thead>
            <tr>
                <th>id</th>
                <th>Name</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sim_run_batches as $sim_run_batch)
            <tr>
                <td>{{ $sim_run_batch->id }}</id>
                <td>
                    <a href="/strategies/{{ $sim_run_batch->id }}">{{ $sim_run_batch->id }}</a>
                </td>
                <td></td>
            </tr>
            @endforeach
        </tbody>
    </table>    
</x-layout>