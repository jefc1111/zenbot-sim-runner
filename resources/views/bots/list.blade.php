<x-layout>
    <h2>Active bots</h2>
    @include('bots.list_table', ['bots' => $bots->filter(fn($b) => $b->active)])
    <h2>Inactive bots</h2>
    @include('bots.list_table', ['bots' => $bots->filter(fn($b) => ! $b->active)])
</x-layout>