<span style="padding: 16px 0 0 4px; display: {{ Auth::user()->has_sim_time() ? 'none' : 'block' }}" class="text-danger">                        
    <ion-icon name="warning-outline"></ion-icon> You do not have enough sim time available to run simulations.
</span>