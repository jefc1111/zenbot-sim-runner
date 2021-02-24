<html>
    <head>
        <title>{{ $title ?? 'The zen Zenbot bot' }}</title>
        <link rel="stylesheet" href="/css/app.css">
    </head>
    <body>
        <h1>
            <a href="/">The zen Zenbot bot</a>
        </h1>
        <hr />        
        {{ $slot }}
        <hr />
        <a href="/">Home</a>
        <br />
        <a href="/strategies">List strategies</a>  
        <br />
        <a href="/strategy-options">List strategy options</a>        
    </body>
</html>