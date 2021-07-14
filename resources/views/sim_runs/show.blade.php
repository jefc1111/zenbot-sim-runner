<x-layout>
    <h2>Sim run {{ $sim_run->id }}</h2>
    <p>
        From batch: <a href="/sim-run-batches/{{ $sim_run->sim_run_batch->id }}">{{ $sim_run->sim_run_batch->name }}</a><br>
        Strategy: <a href="/strategies/{{ $sim_run->strategy->id }}">{{ $sim_run->strategy->name }}</a><br>
    </p>
    <h4>Options</h4>
    <table class="table table-sm table-bordered">
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
    <p>
        <code>{{ $sim_run->cmd() }}</code>
    </p>
    <button {{ Auth::user()->has_sim_time() ? null : 'disabled' }} type="button" class="btn btn-success" id="run">
        Initiate sim run
    </button>
    @include('shared.no_sim_time_warning')
    <p>
        <code>{{ $sim_run->log }}</code>
    </p>
    @if($sim_run->result)
    <span>vs. buy hold: {{ $sim_run->vs_buy_hold }}</span>
    <pre id="result">
        {{ print_r($sim_run->result) }}
    </pre>
    @endif
    <script>
        $("#run").click(function() {
            $.get("/sim-runs/run/{{ $sim_run->id }}", function(res) {
                alert(res.msg)
            });
        });
    </script>
</x-layout>