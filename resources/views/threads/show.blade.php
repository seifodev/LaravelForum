@extends('layouts.app')

@section('content')

    <thread :initial-replies-count="{{$thread->replies_count}}" inline-template>
        <div class="container">

            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-heading clearfix">
                            @can('update', $thread)
                            <div class="pull-right">
                                {!! Form::open(['method' => 'DELETE', 'url' => $thread->path()]) !!}
                                {!! Form::submit('delete thread', ['class' => 'btn btn-danger btn-sm']) !!}
                                {!! Form::close() !!}
                            </div>
                            @endcan
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
                    <replies @removed="repliesCount--" @added="repliesCount++"></replies>
                    <!-- End Replies -->


                    @include('threads.errors') <!-- Show Form Errors -->

                </div><!-- end .col -->

                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            This thread was published {{ $thread->created_at->diffForHumans() }} ago, <br>
                            by
                            <a href="{{ $thread->creator->profilePath() }}">{{ $thread->creator->name }}</a>,
                            & currently has <span v-text="repliesCount"></span> {{ str_plural('comment', $thread->replies_count) }}.
                            <subscribe active="{{$thread->is_subscribed_to}}"></subscribe>
                        </div>
                    </div><!-- end .panel -->
                </div>

            </div><!-- end .row -->


        </div><!-- end .container -->
    </thread>

@endsection
