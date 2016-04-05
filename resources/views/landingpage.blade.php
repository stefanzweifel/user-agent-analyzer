@extends('master')

@section('content')

    <h1>UA Analzer</h1>

    {!! Form::open(['route' => 'process.store']) !!}

        <div class="form-group">
            {!! Form::label('email', 'Email') !!}
            {!! Form::email('email', '', ['class' => 'form-control', 'placeholder' => 'Your email to start the process!']) !!}
        </div>

        <button type="submit" class="btn btn-success btn-large">Let's do this!</button>

    {!! Form::close() !!}

@stop
