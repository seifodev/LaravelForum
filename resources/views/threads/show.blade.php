@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>
                            {{ $thread->title }}
                        </strong>
                    </div>

                    <div class="panel-body">
                            {{ $thread->body }}
                        <hr>
                    </div>

                </div><!-- end .panel -->

                <!-- Start Replies -->
                @foreach($replies as $reply)
                    @include('threads.reply')
                @endforeach
                <!-- End Replies -->



                {{ $replies->links() }}

                @include('threads.errors') <!-- Show Form Errors -->

                <!-- Start Replying Form -->
                @if(auth()->check())

                    {!! Form::open([
                        'method'    => 'POST',
                        'url'       => "{$thread->path()}/replies"
                    ]) !!}

                    <div class="form-group">
                        {!! Form::textarea('body', null, ['class' => 'form-control', 'rows' => 5, 'required', 'placeholder' => 'Have something to say?']) !!}
                    </div>

                    {!! Form::submit('Post', ['class' => 'btn btn-primary']) !!}

                    {!! Form::close() !!}

                @else
                    <p>Please <a href="{{ route('login') }}">sign in</a> to participate in this discussion.</p>
                @endif
                <!-- End Replying Form -->


            </div><!-- end .col -->

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        This thread was published {{ $thread->created_at->diffForHumans() }} ago, <br>
                        by
                        <a href="#">{{ $thread->creator->name }}</a>,
                        & currently has {{ $thread->replies_count }} {{ str_plural('comment', $thread->replies_count) }}.
                    </div>
                </div><!-- end .panel -->
            </div>

        </div><!-- end .row -->



        <br>
        <br>
        <br>
        <br>
        <br>

    </div><!-- end .container -->




@endsection
