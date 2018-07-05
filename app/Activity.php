<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    public static function boot()
    {
        parent::boot();
        static::addGlobalScope('subject', function($activity) {
            $activity->with('subject');
        });
    }

    protected $guarded = [];

    public function subject()
    {
        return $this->morphTo();
    }

    public static function feed($user, $take = 50)
    {
        return  static::where('user_id', $user->id)->latest()->take($take)->get()->groupBy(function ($activity) {
            return $activity->created_at->format('Y-m-d');
        });
    }

}
