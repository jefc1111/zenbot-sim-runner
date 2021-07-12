<table id="sim-runs-table" class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>id</th>
            <th>Strategy</th>
            <th>Qty trades</th>
            <th>Runtime</th>
            <th>Profit</th>                
            <th>vs. buy hold</th>
        </tr>
    </thead>
    <tbody>
        @foreach($batch->sim_runs as $sim_run)
        <tr>
            <td>
                <a href="/sim-runs/{{ $sim_run->id }}">{{ $sim_run->id }}</a>
            </id>
            <td>
                <a href="/strategies/{{ $sim_run->strategy->id }}">{{ $sim_run->strategy->name }}</a>
            </td>
            <td>{{ $sim_run->result('total_trades') }}</td>
            <td>{{ gmdate("i:s", $sim_run->runtime) }}</td>
            <td>{{ $sim_run->result_pct('profit', 4) }}</td>
            <td>{{ $sim_run->result_pct('vs_buy_hold') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>  

<script>
    $(document).ready(function () {
        $('table#sim-runs-table').DataTable();                   
    });
</script>