<table class="sim-run-options-table">
    <thead>
    </thead>
    <tbody>
        @foreach($sim_run->strategy_options->filter(fn($opt) => $varying_options_by_strategy_id[$opt->strategy_id]->contains($opt)) as $option)
        <tr>
            <td>{{ $option->name }}</id>
            <td>{{ $option->value }}</id>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
    </tfoot>
</table>  