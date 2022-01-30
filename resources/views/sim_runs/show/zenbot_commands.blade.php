<h5>Run simulation</h5>
<p>
    <code class="text-secondary">
        {{ $sim_run->sim_cmd() }}
    </code>
</p>
<h5>Paper trade</h5>
<p>
    <code class="text-secondary">
        {{ $sim_run->paper_trade_cmd() }}
    </code>
</p>
<h5>Live trade</h5>
<p>
    <code class="text-secondary">
        {{ $sim_run->live_trade_cmd() }}
    </code>
</p>
<button {{ Auth::user()->has_sim_time() ? null : 'disabled' }} type="button" class="btn btn-success" id="run">
    Initiate sim run <ion-icon name="play"></ion-icon>
</button>
@include('shared.no_sim_time_warning')