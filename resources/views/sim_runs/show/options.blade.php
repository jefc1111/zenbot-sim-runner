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