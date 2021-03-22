<x-layout>
    <style>
        span#run {
            background: #eee;
            padding: 5px;
        }    

        span#run:hover {
            cursor: pointer;
            background: #ddd;
        }    
    </style>
    <h2>Sim run {{ $sim_run->id }}</h2>
    From batch: <a href="/sim-run-batches/{{ $sim_run->sim_run_batch->id }}">{{ $sim_run->sim_run_batch->name }}</a><br>
    <h3>Options</h3>
    <table>
        <thead>
            <tr>
                <th>id</th>
                <th>Name</th>                
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sim_run->strategy_options as $option)
            <tr>
                <td>{{ $option->id }}</id>
                <td>{{ $option->name }}</id>
                <td>{{ $option->value }}</id>
            </tr>
            @endforeach
        </tbody>
    </table>    
    <p>{{ $sim_run->cmd() }}</p>
    <span id="run">RUN</span>

    <script>
        $("#run").click(function() {
            $.get("/sim-runs/run/{{ $sim_run->id }}", function(res) {
                console.log(res)
            });
        });
    </script>
</x-layout>