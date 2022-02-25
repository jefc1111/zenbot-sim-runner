<td>
    {{ $snapshot->qty_trades }}
</td>
@include('table_components.good_bad_cell', [
    'content' => $snapshot->profit.'%',
    'value' => $snapshot->profit                    
])
@include('table_components.good_bad_cell', [
    'content' => $snapshot->buy_hold_profit.'%',
    'value' => $snapshot->buy_hold_profit                    
])
@include('table_components.good_bad_cell', [
    'content' => ($snapshot->profit - $snapshot->buy_hold_profit).'%',
    'value' => ($snapshot->profit - $snapshot->buy_hold_profit)                    
])
<td>
    {{ number_format($snapshot->asset_amount, 8) }}
</td>
<td>
    {{ number_format($snapshot->currency_amount, 8) }}
</td>