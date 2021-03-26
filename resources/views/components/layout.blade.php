<html>
    <head>
        <title>{{ $title ?? 'Zenbot sim runner' }}</title>
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>        
    </head>
    <body>
        <div class="container">
            <h1>
                <a href="/">Zenbot sim runner</a>
            </h1>
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
        </div>        
    </body>
</html>