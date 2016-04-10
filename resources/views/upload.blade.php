@extends('master')

@section('content')

    <div class="measure">

        @if ($process->isFinished())

            <h3>Data processed. Here's your report.</h3>
            <p class="lh-copy">We parsed your file in {{ $process->processingDuration('InSeconds') }} seconds and detected <strong>{{ $process->report->total }} User Agents</strong>.</p>

            <section class="cf">
                <div class="fl mv3 mv0-ns w-100 w-33-ns">
                    <p class="f2 ma0 b red">{{ $process->report->desktop }}</p>
                    <p class="f4 ma0 tracked ttu">Desktop</p>
                </div>
                <div class="fl mv3 mv0-ns w-100 w-33-ns">
                    <p class="f2 ma0 b red">{{ $process->report->tablet }}</p>
                    <p class="f4 ma0 tracked ttu">Tablet</p>
                </div>
                <div class="fl mv3 mv0-ns w-100 w-33-ns">
                    <p class="f2 ma0 b red">{{ $process->report->mobile }}</p>
                    <p class="f4 ma0 tracked ttu">Mobile</p>
                </div>
            </section>

            <canvas id="ua-report" class="mv4"></canvas>

            <h3 class="f4 b tracked">Export</h3>
            <ul>
                <li>
                    {!! link_to_route('process.downloads.csv', 'CSV', [$process->id], ['class' => 'link dim']) !!}
                </li>
                <li>
                    {!! link_to_route('process.downloads.xls', 'XLS', [$process->id], ['class' => 'link dim']) !!}
                </li>
                <li><a href="#" id="js-download--image" taget="blank" download="ua-diagram.png" class="link dim">PNG</a></li>
            </ul>

        @elseif($process->isProcessing())

            <h3>Give us a minute.</h3>
            <p class="lh-copy">We're currently processing your uploaded file. This shouldn't take longer than a few minutes (depending on the size of your file). Check back in a minute or wait for an email from us.</p>

            <p class="lh-copy i f6">Processing started {{ $process->start_at->diffForHumans() }}</p>

        @elseif($process->isExpired())

            <h3>Whoops!</h3>
            <p class="lh-copy">Sorry, but your link expired. Please {!! link_to_route('home', 'request a new upload-link', [], ['class' => 'link dim brand']) !!} and upload your file in the next 24 hours.</p>

        @elseif($process->hasReceivedFile())

            <h3>Give us!</h3>
            <p class="lh-copy">
                We already got a file for this process.
            </p>

        @else
            <h3>Hey there!</h3>
            <p class="lh-copy">
                Alright. You can now upload a CSV file with your data. The User Agent string has to be in the first column! Your dataset will be processed and you get another mail with your results.
            </p>

            @if (!Session::has('success'))

                {!! Form::open(['route' => ['process.update', $process->id], 'method' => 'PATCH', 'files' => true]) !!}

                    {!! Form::file('file', ['class' => 'input-reset mv2 pa2 w-100']) !!}
                    <button type="submit" class="btn btn-success btn-large">Upload and start process</button>

                {!! Form::close() !!}

            @else

                <div class="ba pa3 measure">
                    {{ session('success') }}
                </div>

            @endif

            <p class="lh-copy f6 i">
                This upload will expire in {{ $process->expires_at->diffForHumans() }}
            </p>

        @endif

    </div>

@stop


@section('scripts')

    @if ($process->isFinished())

    <script>

        var ctx = document.getElementById("ua-report").getContext("2d");
        ctx.canvas.width = 150;
        ctx.canvas.height = 150;


        var data = [
            {
                value: {{ $process->report->desktop }},
                color:"#3F51B5",
                highlight: "#5C6BC0",
                label: "Desktop"
            },
            {
                value: {{ $process->report->mobile }},
                color: "#009688",
                highlight: "#26A69A",
                label: "Mobile"
            },
            {
                value: {{ $process->report->tablet }},
                color: "#F44336",
                highlight: "#EF5350",
                label: "Tablet"
            },
            {
                value: {{ $process->report->unkown }},
                color: "#ff6600",
                highlight: "#EF5350",
                label: "Unkown"
            }
        ]

        var myPieChart = new Chart(ctx).Pie(data, {
            animation: false,
            segmentStrokeWidth : 3,
            percentageInnerCutout : 50,
            legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"

        });

        var url = document.getElementById("ua-report").toDataURL();



        // window.open(url);

        document.getElementById("js-download--image").href = url;


    </script>
    @endif

@stop