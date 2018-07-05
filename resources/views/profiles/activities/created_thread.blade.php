@component('profiles.activities.activity')
    @slot('heading')
        <a href="{{ $profileUser->profilePath() }}">{{ $profileUser->name }}</a> published
        <a href="{{ $activity->subject->path() }}">{{ $activity->subject->title }}</a>
        <small>{{ $activity->created_at->diffForHumans() }}</small>
    @endslot
    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent
