<html>
    <head>
        <title>{{ $title ?? 'COCKS AND DICKS' }}</title>
        <link rel="stylesheet" href="/css/app.css">
    </head>
    <body>
        <h1>
            <a href="/strategies">The zen Zenbot bot</a>
        </h1>        
        {{ $slot }}
    </body>
</html>