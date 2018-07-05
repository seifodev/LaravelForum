<?php

namespace App\Http\Controllers;

use App\Rules\SpamFree;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Thread;
use App\Reply;
use Gate;
class RepliesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    /**
     * @param $channel
     * @param Thread $thread
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index($channel, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    /**
     * @param $channelSlug
     * @param Thread $thread
     * @return \Illuminate\Database\Eloquent\Model
     * @throws AuthorizationException
     */
    public function store($channelSlug, Thread $thread)
    {

        if(Gate::denies('create', new Reply))
        {
            return response('Please wait a minute before replying again.', 442);
        }

        try
        {
            $this->validate(request(), [
                'body'      => ['required', new SpamFree],
            ]);

            $reply = $thread->addReply([
                'body'      => request('body'),
                'user_id'   => Auth::user()->id,
                'thread_id' => $thread->id,
            ]);

            if(request()->ajax())
            {
                return $reply->load('owner');
            }
        } catch (\Exception $e)
        {
            return response('Sorry, your reply could not be saved at te current time.', 442);
        }

//        return back()->with('flash', 'Your reply was published');
    }

    /**
     * @param Reply $reply
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Reply $reply)
    {
//        return $reply;
        $this->authorize('update', $reply);

        if(request()->ajax())
        {
            $reply->delete();
            return response(['status' => 'Reply deleted']);
        }
        $reply->delete();
        return back()->with('flash', 'Your reply has been deleted successfully');
    }

    /**
     * @param Reply $reply
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Reply $reply)
    {
        if(Gate::denies('update', $reply))
        {
            return response('You are not allowed to update this reply.', 442);
        }

        try
        {
            $this->validate(request(), [
                'body'      => ['required', new SpamFree],
            ]);

            $reply->update(['body' => request()->get('body')]);
            return response($reply->body);
        } catch (\Exception $e)
        {
            return response('Sorry, your reply could not be saved at te current time.', 422);
        }


    }

}
