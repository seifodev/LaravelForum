<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function profilePath()
    {
        return '/profiles/' . $this->name;
    }

    public function threads()
    {
        return $this->hasMany('App\Thread')->latest();
    }

    public function replies()
    {
        return $this->hasMany('App\Reply');
    }

    public function activities()
    {
//        return $this->hasMany('App\Activity')->with('subject');
        return $this->hasMany('App\Activity');
    }

    public function visitedThreadCacheKey($thread)
    {
        return sprintf('users.%s.visits.%s', auth()->id(), $thread->id);
    }

    public function read($thread)
    {
        cache()->forever($this->visitedThreadCacheKey($thread), \Carbon\Carbon::now());
    }

    public function lastReply()
    {
        return $this->hasOne('App\Reply')->latest();
//        return $this->replies()->latest()->first();
    }

}
