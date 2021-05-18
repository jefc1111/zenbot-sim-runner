<table id="batch-ancestry-table" class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>id</th>
            <th>Name</th>
            <th>Qty sim runs</th>
            <th>Generation no.</th>                
            <th>Best vs. buy hold</th>
            <th>Created at</th>
        </tr>
    </thead>
    <tbody>
        @foreach(collect([$batch])->merge($batch->batch_ancestry_list())->reverse()->values() as $i => $ancestor_batch)
        <tr>
            <td>{{ $ancestor_batch->id }}</td>
            <td>
                <a href="/sim-run-batches/{{ $ancestor_batch->id }}">{{ $ancestor_batch->name }}</a>
            </id>
            <td>{{ $ancestor_batch->sim_runs->count() }}</td>
            <td>{{ $i }} {{ $i === 0 ? '(user created)' : null }} {{ $i === $batch->batch_ancestry_list()->count() ? '(this batch)' : null }}</td>
            <td>{{ $ancestor_batch->best_vs_buy_hold() }}</td>
            <td>{{ $ancestor_batch->created_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>  

<script>
    $(document).ready(function () {
        $('table#sim-runs-table').DataTable();                   
    });
</script>