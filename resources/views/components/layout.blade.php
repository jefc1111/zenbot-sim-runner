<html>
    <head>
        <title>{{ $title ?? 'Zenbot sim runner' }}</title>
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">  
        <script src="https://code.highcharts.com/highcharts.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <h1>
                    <a href="/">Zenbot sim runner</a>
                </h1>
            </div>
            <hr />        
            {{ $slot }}
            <hr />
            <a href="/">Home</a>            
            <small class="text-secondary">|</small>
            <a href="/strategies">List strategies</a>  
            <small class="text-secondary">|</small>
            <a href="/exchanges">List exchanges</a>  
            <small class="text-secondary">|</small>
            <a href="/strategy-options">List strategy options</a>
            <small class="text-secondary">|</small>
            <a href="/sim-run-batches">List sim run batches</a>

            <span class="float-right">
                Hello {{ Auth::user()->name }} 
                (available sim time 
                <span class="{{ Auth::user()->available_sim_time_class() }}">
                    {{ Auth::user()->available_sim_time() }}
                </span>
                )
                <small class="text-secondary">|</small>
                <a href="/logout">Logout</a>
            </span>            
        </div>        
    </body>
</html>