@component('profiles.activities.activity')
    @slot('heading')
        {{ $profileUser->name}} replied to
        "<a href="{{ action('ThreadsController@show', [$activity->subject->thread->channel, $activity->subject->thread]) }}">
            {{ $activity->subject->thread->title }}
        </a>"
    @endslot

    @slot('body')
        {{$activity->subject->body}}
    @endslot
@endcomponent
