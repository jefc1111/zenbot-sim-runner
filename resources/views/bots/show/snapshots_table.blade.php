<table id="snapshots-table" class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>id</th>
            @include('bots.shared.snapshot_header_cells')
        </tr>
    </thead>
    <tbody>
        @foreach($bot->snapshots->reverse() as $snapshot)
        <tr>
            <td>{{ $snapshot->id }}</id>
            @include('bots.shared.snapshot_body_cells', ['snapshot' => $snapshot])
        </tr>
        @endforeach
    </tbody>
</table>  

<script>    
    $(document).ready(function () {
        $('table#snapshots-table').DataTable();
    });
</script>
