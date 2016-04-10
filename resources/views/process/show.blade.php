@extends('master')

@section('content')

    <div class="measure">

        @if ($process->isFinished())

            @include('process.report')

        @elseif($process->isProcessing())

            @include('process.processing')

        @elseif($process->isExpired())

            @include('process.expired')

        @elseif($process->hasReceivedFile())

            @include('process.has_received_file')

        @else

            @include('process/upload')

        @endif

    </div>

@stop