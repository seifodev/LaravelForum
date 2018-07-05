@extends('layouts.app')

@section('content')
    <div class="container">

        <h2 class="page-header">
            Threads
        </h2>

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @forelse($threads as $thread)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="replies pull-right">
                            <a href="{{ $thread->path() }}">
                                {{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}
                            </a>
                        </div>
                        <h4>
                            <a href="{{ $thread->path() }}">
                                @if($thread->hasUpdatesFor())
                                    <strong>
                                        {{$thread->title}}
                                    </strong>
                                @else
                                    {{$thread->title}}
                                @endif
                            </a>
                        </h4>
                    </div>

                    <div class="panel-body">
                        {{$thread->body}}
                    </div>
                </div>
                @empty
                    <p class="alert alert-info">There are no threads.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
