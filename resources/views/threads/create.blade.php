@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @include('threads.errors')
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Create a New Thread</div>

                    <div class="panel-body">

                        {!! Form::open([
                            'method'    => 'POST',
                            'action'    => 'ThreadsController@store',
                            'class'     => 'form-horizontal'
                        ]) !!}

                        <div class="form-group">
                            {!! Form::label('channels', 'Channels', ['class' => 'control-label col-md-2']) !!}
                            <div class="col-md-9">
                                {!! Form::select('channel_id', ['' => 'Choose One ...'] + $channelsList, null, ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('title', 'Title', ['class' => 'control-label col-md-2']) !!}
                            <div class="col-md-9">
                                {!! Form::text('title', null, ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('body', 'Body', ['class' => 'control-label col-md-2']) !!}
                            <div class="col-md-9">
                                {!! Form::textarea('body', null, ['class' => 'form-control', 'rows' => 7, 'required']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-9 col-md-offset-2">
                                {!! Form::submit('Post', ['class' => 'btn btn-primary']) !!}
                            </div>
                        </div>

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
