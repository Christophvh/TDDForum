<div class="panel panel-default">
    <div class="panel-heading">
    <div class="level">
        <h5 class="flex">
            <a href="{{ action('ProfilesController@show', $reply->owner) }}">
                {{$reply->owner->name}}
            </a> said {{$reply->created_at->diffForHumans()}}
        </h5>
        <div>
            <form action="{{ action('FavoritesController@store', $reply) }}" method="POST">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-default" {{ $reply->isFavorited() ? 'disabled' : '' }}>
                    {{ $reply->favorites_count }} {{ str_plural('Favorite', $reply->favorites_count ) }}
                </button>
            </form>
        </div>
    </div>
    </div>
    <div class="panel-body">
        {{$reply->body}}
    </div>
</div>