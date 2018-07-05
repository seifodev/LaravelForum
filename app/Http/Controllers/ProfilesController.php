<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class ProfilesController extends Controller
{
    public function show(User $user)
    {
        $profileUser = $user;
        $activities = \App\Activity::feed($user);
//        return $activities;
        return view('profiles.show', compact('profileUser', 'activities'));
    }

}
