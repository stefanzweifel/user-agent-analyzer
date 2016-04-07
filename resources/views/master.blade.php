<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>User Agent Analyzer</title>

        <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="https://npmcdn.com/tachyons@4.0.0-beta.12/css/tachyons.min.css">
        <link rel="stylesheet" href="/css/app.css">

    </head>
    <body>

        <div class="pa3 ph5-ns pv2-ns">

            <nav class="pv3 pv4-ns">
                <a class="link brand b f2 db mb1 mb1-ns" href="/" title="Home">User Agent Analyzer</a>
                <p class="f3 ma0 dim">Get useful charts from User Agent Strings</p>
            </nav>

            <section>

                @if ($errors->has())
                    <div class="ba pa3 measure red">
                        @foreach ($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                @endif

                @yield('content')

            </section>

            <footer class="pv3 pv4-m pv4-l mid-gray">
                <div class="mt3">
                    <a class="f6 dib ph2 link mid-gray dim" href="https://stefanzweifel.io/imprint">Terms of Service</a>
                    <a class="f6 dib ph2 link mid-gray dim" href="https://stefanzweifel.io/imprint">Imprint</a>
                    <a class="f6 dib ph2 link mid-gray dim" href="https://stefanzweifel.io">A sideproject by stefanzweifel</a>
                </div>
            </footer>

        </div>

        <script src="/js/main.js"></script>
        @yield('scripts')
    </body>
</html>
