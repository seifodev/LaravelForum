<?php

namespace App\Http\Controllers;


use App\Rules\SpamFree;
use App\Thread;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Channel;
use App\Filters\ThreadFilters;

class ThreadsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['show', 'index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Channel $channel
     * @param ThreadFilters $filters
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilters $filters)
    {
        $threads = $this->getThreads($channel, $filters);

        if(request()->wantsJson()) return $threads;
        return view('threads.index', compact('threads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $channelsList = Channel::pluck('name', 'id')->all();
        return view('threads.create', compact('channelsList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            'channel_id'    => 'required|exists:channels,id',
            'title'         => ['required', new SpamFree],
            'body'          => ['required', new SpamFree],

        ]);

        $thread = Thread::create([
            'channel_id'    => $request->channel_id,
            'user_id'       => Auth::user()->id,
            'title'         => $request->title,
            'body'          => $request->body,
        ]);


        return redirect($thread->path())
                ->with('flash', 'Your thread has been published');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread $thread
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function show($channelSlug, Thread $thread)
    {

        if(auth()->check())
        {
            auth()->user()->read($thread);
        }

        $thread->append('is_subscribed_to');
        return view('threads.show', compact('thread'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread $thread
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        // Test Purpose only
        if(request()->wantsJson())
        {
            $thread->replies->each(function ($reply){
                $reply->delete();
            });
            $thread->delete();
            return response('', 401);
        }

        $thread->delete();
        return redirect('/threads');
    }

    protected function getThreads(Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::latest()->filter($filters);

        if ($channel->exists) {
            $threads = $threads->whereChannelId($channel->id);
        }
        return $threads->get();

    }


}
