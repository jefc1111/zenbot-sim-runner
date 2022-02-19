<x-layout>
    <table class="table table-sm">
    <thead>
    </thead>
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