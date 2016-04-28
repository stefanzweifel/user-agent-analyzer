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
    <div class="fl mv3 mv0-ns w-100 w-33-ns">
        <p class="f2 ma0 b red">{{ $process->report->robots }}</p>
        <p class="f4 ma0 tracked ttu">Robots</p>
    </div>
    <div class="fl mv3 mv0-ns w-100 w-33-ns">
        <p class="f2 ma0 b red">{{ $process->report->other }}</p>
        <p class="f4 ma0 tracked ttu">Other</p>
    </div>
    <div class="fl mv3 mv0-ns w-100 w-33-ns">
        <p class="f2 ma0 b red">{{ $process->report->unknown }}</p>
        <p class="f4 ma0 tracked ttu">Unknown</p>
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
</ul>

@section('scripts')

    @parent

    <script>
        renderChart({!! $process->report->toJson() !!})
    </script>

@stop