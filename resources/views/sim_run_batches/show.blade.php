<x-layout>
    <h2>Sim run batch {{ $batch->id }}</h2>
    <table>
        <thead>
            <tr>
                <th>id</th>
                <th>Strategy</th>
                <th>cmd</th>
            </tr>
        </thead>
        <tbody>
            @foreach($batch->sim_runs as $sim_run)
            <tr>
                <td>{{ $sim_run->id }}</id>
                <td>{{ $sim_run->strategy->name }}</id>
                <td>{{ $sim_run->cmd() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>    
</x-layout>