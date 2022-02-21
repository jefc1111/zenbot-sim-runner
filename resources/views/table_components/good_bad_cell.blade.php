<td 
    @if($value != 0)
    class="text-{{ $value < 0 ? 'danger' : 'success' }}"
    @endif
>
    {{ $bot->latest_snapshot ? ($bot->latest_snapshot->profit - $bot->latest_snapshot->buy_hold_profit).'%' : null }}
</td>