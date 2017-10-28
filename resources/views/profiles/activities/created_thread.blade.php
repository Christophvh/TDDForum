@component('profiles.activities.activity')
    @slot('heading')
        {{ $profileUser->name}} posted
        "<a href="{{ action('ThreadsController@show', [$activity->subject->channel, $activity->subject]) }}">
            {{ $activity->subject->title }}
        </a>"
    @endslot

    @slot('body')
        {{$activity->subject->body}}
    @endslot
@endcomponent

