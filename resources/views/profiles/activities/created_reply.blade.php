@component('profiles.activities.activity')
    @slot('heading')
        <a href="{{ $profileUser->profilePath() }}">
            <strong>{{ $profileUser->name }}</strong>
        </a> replied to
        "<a href="{{ $activity->subject->thread->path() }}">{{ $activity->subject->thread->title }}</a>"
        <small>{{ $activity->created_at->diffForHumans() }}</small>
    @endslot
    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent
