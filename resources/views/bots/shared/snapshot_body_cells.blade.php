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
    {{ number_format($snapshot->asset_amount, 2) }}
</td>
<td>
    {{ number_format($snapshot->asset_capital, 2) }}
</td>
<td>
    {{ number_format($snapshot->currency_amount, 2) }}
</td>
<td>
    <div class="asseet-capital-split">
        <div style="width: {{ number_format($snapshot->asset_pct(), 0) }}px">
            <span style="visibility: {{ $snapshot->asset_pct() > 30 ? 'visible' : 'hidden' }}; ">{{ $snapshot->asset }}</span>
        </div>
        <div style="width: {{ number_format($snapshot->currency_pct(), 0) }}">
            <span style="visibility: {{ $snapshot->currency_pct() > 30 ? 'visible' : 'hidden' }}; ">{{ $snapshot->currency }}</span>
        </div>
    </div>
</td>
<td>
    {{ $snapshot->qty_trades }}
</td>