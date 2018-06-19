<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <strong>
            <a href="#">{{$reply->owner->name}}</a>
        </strong>
        {{$thread->created_at->diffForHumans()}}
        <div class="pull-right">

            {!! Form::open([
                'method'    => 'POST',
                'url'       => "/replies/{$reply->id}/favourites",
            ]) !!}

            <button type="submit" class="btn btn-sm {{ $reply->isFavourited() ? 'btn-danger' : '' }} ">
                {{ $reply->favourites_count}} {{str_plural('Favourite', $reply->favourites_count)}}
            </button>

            {!! Form::close() !!}

        </div>
    </div>

    <div class="panel-body">
        {{$reply->body}}
    </div>

</div>
