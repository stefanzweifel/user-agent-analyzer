<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>User Agent Analyzer</title>

        <link rel="stylesheet" href="{{ elixir('css/app.css') }}">
    </head>
    <body>

        <div class="pa3 ph5-ns pv3-ns application-container">

            <nav class="pv3 pv4-ns">
                <a class="link brand b f3 f2-ns db mb1 mb1-ns marker" href="/" title="Home">User Agent Analyzer</a>
                <p class="f6 f5-ns ma0 dim pointer">Get useful charts from User Agent Strings</p>
            </nav>

            <section>

                @if (isset($errors) && $errors->has())
                    <div class="ba pa3 measure red">
                        @foreach ($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                @endif

                @yield('content')

            </section>

        </div>

        <footer class="pa3 ph5-ns pv2-ns pv3 pv4-m pv4-l white">
            <div class="">
                <a class="f6 dib pv2 pv0-ns ph2 link white dim" href="https://github.com/stefanzweifel/user-agent-analyzer">Source Code</a>
                <a class="f6 dib pv2 pv0-ns ph2 link white dim" href="/terms-of-service">Terms of Service</a>
                <a class="f6 dib pv2 pv0-ns ph2 link white dim" href="https://stefanzweifel.io/imprint">Imprint</a>
                <a class="f6 dib pv2 pv0-ns ph2 link white dim" href="https://stefanzweifel.io">A sideproject by stefanzweifel</a>
            </div>
        </footer>

        <script src="{{ elixir('js/main.js') }}"></script>
        @yield('scripts')
    </body>
</html>
