@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="level">
                            <span class="flex">
                                <a href="{{ action('ProfilesController@show', $thread->creator) }}">
                                    {{$thread->creator->name}}
                                </a> posted:
                                {{$thread->title}}
                            </span>
                            @can('update', $thread)
                                <form action="{{ action('ThreadsController@destroy',[$thread->channel, $thread]) }}"
                                      method="POST">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button type="submit" class="btn btn-link">Delete Thread</button>
                                </form>
                            @endcan
                        </div>
                    </div>
                    <div class="panel-body">
                        {{$thread->body}}
                    </div>
                </div>
                @foreach($replies as $reply)
                    @include('threads.reply')
                @endforeach

                {{ $replies->links() }}
                @if(auth()->check())
                    <form method="POST" action="{{action('RepliesController@store', [$thread->channel, $thread])}}">
                        {{csrf_field()}}
                        <div class="form-group">
                            <textarea name="body" id="body" class="form-control"
                                      placeholder="Say something.."></textarea>
                        </div>
                        <button type="submit" class="btn btn-default">Post</button>
                    </form>
                @else
                    <p class="text-center">Please <a href="{{action('Auth\LoginController@login')}}">sign in</a> to
                        participate
                        in this discussion</p>
                @endif
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        This thread was published {{ $thread->created_at->diffForHumans() }} by
                        <a href="{{ action('ProfilesController@show', $thread->creator) }}">{{ $thread->creator->name }}</a>,and
                        currently has
                        {{ $thread->replies_count }} {{ str_plural('comment', $thread->replies_count) }}.
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
