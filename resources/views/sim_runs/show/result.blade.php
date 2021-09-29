@if($sim_run->log)
<p>
    <code>{{ $sim_run->log }}</code>
</p>
@endif
@if($sim_run->result)
<span>vs. buy hold: {{ $sim_run->vs_buy_hold }}</span>
<pre id="result">
    {{ print_r($sim_run->result) }}
</pre>
@else
No results found
@endif