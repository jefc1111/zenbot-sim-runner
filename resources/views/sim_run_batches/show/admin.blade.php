@include('sim_run_batches.show.admin_btn', [
    'href' => "copy/$batch->id",
    'method' => 'get',
    'btn_text' => 'Copy',
    'description' => 'Copy basic batch info only. Strategies can be selected and refined after copying.'
])
@include('sim_run_batches.show.admin_btn', [
    'href' => "prune/$batch->id",
    'method' => 'get',
    'btn_text' => 'Prune',
    'description' => 'Soft deletes any sim runs that are incomplete or errored (so that partially complete batches can be analysed).'
])
@include('sim_run_batches.show.admin_btn', [
    'href' => "reset/$batch->id",
    'method' => 'get',
    'btn_text' => 'Reset',
    'description' => 'Removes all log output and error output for all batches and resets the status.'
])
@include('sim_run_batches.show.admin_btn', [
    'href' => url('/sim-run-batches', ['id' => $batch->id]),
    'method' => 'delete',
    'btn_text' => 'Delete',
    'description' => 'Deletes the entire batch and all sim runs (No confirm screen!).'
])