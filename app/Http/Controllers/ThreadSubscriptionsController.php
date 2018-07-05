<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;

class ThreadSubscriptionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * @param $channel
     * @param Thread $thread
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($channel, Thread $thread)
    {
        $thread->subscribe(auth()->id());
        if(request()->ajax())
        {
            return;
        }
        return back();
    }

    /**
     * @param $channel
     * @param Thread $thread
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function destroy($channel, Thread $thread)
    {
        $thread->unsubscribe();
        if(request()->ajax())
        {
            return;
        }
        return back();
    }
}
