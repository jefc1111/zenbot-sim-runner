<h5>Zenbot cmd</h5>
<p>
    <code style="color: #444">
        {{ $sim_run->cmd() }}
    </code>
</p>
<button {{ Auth::user()->has_sim_time() ? null : 'disabled' }} type="button" class="btn btn-success" id="run">
    Initiate sim run <ion-icon name="play"></ion-icon>
</button>
@include('shared.no_sim_time_warning')