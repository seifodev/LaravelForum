{{--TODO:: check this out--}}
<reply :attributes="{{$reply}}" inline-template v-cloak>
    <div id="reply-{{$reply->id}}" class="panel panel-default">
        <div class="panel-heading clearfix">
            <strong>
                <a href="#">{{$reply->owner->name}}</a>
            </strong>
            {{$reply->created_at->diffForHumans()}}
            <div class="pull-right">

                @auth()
                    <favourite :reply="{{$reply}}"></favourite>
                @endauth
                @guest()
                    <span class="text-warning">
                        {{ $reply->favourites_count}} {{str_plural('Favourite', $reply->favourites_count)}}
                    </span>
                @endguest

            </div>
        </div>

        <div class="panel-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea rows="5" class="form-control" v-model="body"></textarea>
                </div>
                <div class="form-group">
                    <button class="btn btn-sm" @click="editing = false">Cancel</button>
                    <button class="btn btn-sm btn-success" @click="update">Save</button>
                </div>
            </div>
            <div v-else v-text="body"></div>
        </div>

        @can('update', $reply)
            <div class="panel-footer level">
                <button class="btn btn-primary btn-xs" @click="editing = !editing">Edit</button>
                <button class="btn btn-danger btn-xs" @click="deleteReply">Delete</button>
            </div>
        @endcan
    </div>

</reply>