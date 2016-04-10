@extends('master')

@section('content')

    <div class="measure">

        <p class="lh-copy">
            Did you ever had a lot of User Agent strings which weren't categories well?
            Did you ever wanted to know how many mobile users you have?
        </p>
        <p class="lh-copy">
            This little tool let's you upload a CSV file with User Agent strings and analyzes the data for you.
            We will send you a report with your anaylzed data.
        </p>

        <div class="mv3">
            <img src="//placehold.it/200x150" class="di mw-100" alt="">
            <img src="//placehold.it/200x150" class="di mw-100" alt="">
        </div>

        {!! Form::open(['route' => 'process.store']) !!}

            {!! Form::email('email', '', [
                'class' => 'input-reset ba mv2 b--gray pa2 w-100 ',
                'placeholder' => 'Your email to start the process!'
            ]) !!}
            <button type="submit" class="btn btn-success btn-large">Let's do this!</button>

        {!! Form::close() !!}

        <p class="lh-copy f6">
            To prevent abuse of the system you have to provide a valid email-adress.
            We will send you a unique link to upload your file.
            (Your email-adress will be encrypted and after 48 hours removed.)
        </p>

    </div>

@stop
