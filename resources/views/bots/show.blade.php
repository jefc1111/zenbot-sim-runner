<x-layout>
    <div class="row">
        <div class="col-md-6">
            <h3>Bot {{ $bot->name }}</h3>            
        </div>
        <div style="text-align: right; " class="text-muted col-md-6">
            <p>
                <a href="/bots">List bots</a>
            </p>
        </div>
    </div>
    <table class="table table-sm">
        <tbody>
            <tr>
                <td>Name</td>
                <td>{{ $bot->name }}</td>
            </tr>
            <tr>
                <td>Args</td>
                <td>{{ $bot->args }}</td>
            </tr>
            <tr>
                <td>Discord username</td>
                <td>{{ $bot->discord_username }}</td>
            </tr>
            <tr>
                <td>Zenbot port</td>
                <td>{{ $bot->zenbot_port }}</td>
            </tr>
        </tbody>
    </table>
</x-layout>