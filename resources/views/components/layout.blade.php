<html>
    <head>
        <title>{{ $title ?? 'Zenbot sim runner' }}</title>
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">  
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
        <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
    </head>
    <body>
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Zenbot Sim Runner') }}
                    <img height="22" class="" src="{{ asset('images/zsr-logo-no-text.png') }}" alt="ZSR logo">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">

                    </ul>
                </div>
            </div>
        </nav>    
        <br>
        <div class="container">
            @if (\Session::has('success'))
            <div class="alert alert-success">
                <span style="font-size: 1.2em; ">                    
                    <ion-icon name="information-circle-outline"></ion-icon>                                    
                    {!! \Session::get('success') !!}
                </span>
            </div>
            @endif
            @if (\Session::has('error'))
            <div class="alert alert-danger">
                <span style="font-size: 2em; ">                    
                    <ion-icon name="alert-outline"></ion-icon>                                    
                    {!! \Session::get('error') !!}
                </span>
            </div>
            @endif
            @include('shared.private_beta_warning')
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
            <small class="text-secondary">|</small>
            <a href="/shop">Buy sim time</a>

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
        <style>
            table.sim-run-options-table tr td {
                border: none;
                padding: 0 2px;
                font-size: 0.8em;
            }
        </style>
    </body>
</html>