{{--{{$activity}} {{exit}}--}}
@component('profiles.activities.activity')
    @slot('heading')
        <a href="{{ $profileUser->profilePath() }}">{{ $profileUser->name }}</a> liked a
        <a href="{{ $activity->subject->favourited->path() }}">reply</a>
        <small>{{ $activity->created_at->diffForHumans() }}</small>
    @endslot
    @slot('body')
        {{$activity->subject->favourited->body}}
    @endslot
@endcomponent
