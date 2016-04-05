<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>UA Analyzer</title>
        <link rel="stylesheet" href="/css/app.css">
    </head>
    <body>

        <div class="container">

            @if ($errors->has())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif

            @yield('content')

        </div>

        <script src="js/main.js"></script>
    </body>
</html>
