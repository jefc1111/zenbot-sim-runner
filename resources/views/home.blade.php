<x-layout>
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
    @if (Auth::user()->hasRole('admin'))
    <hr />
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
    <hr />
    <h4>Admin</h4>
    <ul class="list-group">
        <li class="list-group-item">
            <a href="/admin">Admin</a>
        </li>
    </ul>    
    @endif
</x-layout>