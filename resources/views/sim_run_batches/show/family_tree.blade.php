<table id="family-tree-table" class="table table-sm table-bordered">
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
        @foreach($batches as $i => $batch)
        <tr class="{{ $batch->id === $subject_batch->id ? 'table-primary' : null }}">
            <td>{{ $batch->id }}</td>
            <td>
                <a href="/sim-run-batches/{{ $batch->id }}">{{ $batch->truncated_name() }}</a>
            </id>
            <td>{{ $batch->sim_runs->count() }}</td>
            <td>{{ $i }} {{ $i === 0 ? '(user created)' : null }} {{ $batch->id === $subject_batch->id ? '(this batch)' : null }}</td>
            <td>{{ round($batch->best_vs_buy_hold(), 4) }}%</td>
            <td>{{ $batch->created_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>  

<script>
    $(document).ready(function () {
        $('#family-tree-table').DataTable({
            // No ordering applied by DataTables during initialisation
            "order": []
        });
    });
</script>