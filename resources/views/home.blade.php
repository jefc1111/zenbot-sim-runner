<x-layout>
    <div class="row">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Available sim time</h5>
                    <p class="card-text">
                        You have {{ Auth::user()->available_sim_time_long_form() }}.
                    </p>
                    <a href="/shop" class="btn btn-primary">Buy more</a>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Sim run results</h5>
                    <p class="card-text">
                        @if (\Auth::user()->sim_run_batches->count() === 0)
                        You don't have any batches yet.
                        @else 
                        You have <a href="/sim-run-batches">{{ \Auth::user()->sim_run_batches->count() }} batch{{ \Auth::user()->sim_run_batches->count() > 1 ? 'es' : '' }}</a> 
                            @if (\Auth::user()->completed_sim_run_batches->count() > 0)
                            <?php $best_sim_run = \Auth::user()->get_best_sim_run(); ?>
                            with a 
                            <a href="/sim-runs/{{ $best_sim_run->id }}">best vs. buy/hold</a> of {{ round($best_sim_run->result('vs_buy_hold'), 2) }}% 
                            @else
                            (none completed) 
                            @endif
                        @endif
                    </p>
                    <a href="/sim-run-batches/create" class="btn btn-primary">
                        @if (\Auth::user()->sim_run_batches->count() === 0)
                        Create one
                        @else
                        Create another batch
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </div>
    <br>
    <ul class="list-group">
        <li class="list-group-item">
            <a href="/sim-run-batches/create">Create sim run batch</a>
        </li>
        <li class="list-group-item">
            <a href="/strategies">List strategies</a>
        </li>
        <li class="list-group-item">
            <a href="/exchanges">List exchanges and products</a>
        </li>
        <li class="list-group-item">
            <a href="/sim-run-batches">List sim run batches</a>
        </li>
    </ul>
    @if (config('zenbot.bot_monitoring.active') && Auth::user()->hasRole('admin'))
    <br>
    <h4>Bot monitoring</h4>
    <ul class="list-group">
        <li class="list-group-item">
            <a href="/bots">List bots</a>
        </li>
    </ul>   
    @endif
    @if (Auth::user()->hasRole('admin'))
    <br>
    <h4>Import data from Zenbot</h4>
    <ul class="list-group">
        <li class="list-group-item">
            <a href="/import-all">Import all (run `php artisan migrate:fresh` first. WARNING: will delete all sim run data.)</a>
        </li>
        <li class="list-group-item">
            <a href="/import-strategies">Import strategies</a>
        </li>
        <li class="list-group-item">
            <a href="/import-exchanges">Import exchanges</a>
        </li>
    </ul>
    <br>
    <h4>Admin</h4>
    <ul class="list-group">
        <li class="list-group-item">
            <a href="/admin">Admin</a>
        </li>
        <li class="list-group-item">
            <a href="/horizon">Horizon dashboard</a>
        </li>
    </ul>    
    @endif
</x-layout>