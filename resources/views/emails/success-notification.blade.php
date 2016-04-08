<style>
    * {
        font-family: sans-serif;
    }
</style>

<h3 style="color: #D32F2F;">User Agent Analyzer</h3>

<p>Hey there</p>
<p>You recently uploaded a file to analyze. This file is now processed and you can see and download reports under the following link.</p>

<p>{!! link_to_route('process.show', 'See report', [$process->id]) !!}</p>

<p>If you're encountering any problems. Just reply to this mail.</p>
<p>Stefan</p>