@extends('master')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading">
            Upload File
        </div>

        <div class="panel-body">

            {!! Form::open(['route' => ['process.update', $process->id], 'method' => 'PATCH', 'files' => true]) !!}

                <div class="form-group">
                    {!! Form::label('file', 'File') !!}
                    {!! Form::file('file') !!}
                </div>

                <button type="submit" class="btn btn-success btn-large">Upload and start process</button>

            {!! Form::close() !!}

        </div>

    </div>

@stop