@include('sim_run_batches.show.admin_btn', [
    'href' => "copy/$batch->id",
    'btn_text' => 'Copy',
    'description' => 'Copy basic batch info only. Strategies can be selected and refined after copying.'
])
@include('sim_run_batches.show.admin_btn', [
    'href' => "prune/$batch->id",
    'btn_text' => 'Prune errors / incomplete',
    'description' => 'Soft deletes any sim runs that are incomplete or errored (so that partially complete batches can be analysed).'
])
@include('sim_run_batches.show.admin_btn', [
    'href' => "reset/$batch->id",
    'btn_text' => 'Reset',
    'description' => 'Removes all log output and error output for all batches and resets the status.'
])
@include('sim_run_batches.show.admin_btn', [
    'href' => "destroy/$batch->id",
    'btn_text' => 'Delete batch',
    'description' => 'Deletes the entire batch and all sim runs (No confirm screen!).'
])