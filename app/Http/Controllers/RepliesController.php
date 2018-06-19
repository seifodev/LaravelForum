<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Thread;

class RepliesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store($channelSlug, Thread $thread)
    {
        $this->validate(request(), [
            'body'      => 'required'
        ]);
        $thread->addReply([
            'body'      => request('body'),
            'user_id'   => Auth::user()->id,
            'thread_id' => $thread->id,
        ]);


        return back();


    }
}
