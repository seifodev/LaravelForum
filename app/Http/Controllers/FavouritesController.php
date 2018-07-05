<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Favourite;
use App\Reply;
use Illuminate\Support\Facades\Auth;

class FavouritesController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth')->only('store');
    }

    public function store(Reply $reply)
    {
//        return $reply;
        $reply->favourite(auth()->id());
//        return back();
    }

    public function destroy(Reply $reply)
    {
        $reply->unfavourite();
    }

}
