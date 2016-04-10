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