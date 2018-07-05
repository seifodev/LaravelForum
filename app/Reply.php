<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Reply extends Model
{
    use Favouritable, RecordsActivity;

    protected $guarded = [];

    protected $with = ['owner', 'favourites'];

    // TODO :: check
    protected $appends = ['isFavourited'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($reply) {
            $reply->thread()->increment('replies_count');
        });

        static::deleting(function ($reply) {
            $reply->thread()->decrement('replies_count');
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
        return $this->belongsTo('App\Thread');
    }

    public function path()
    {
        return $this->thread->path() . '#reply-' . $this->id;
    }

    public function wasJustPublished()
    {
        $carbon = new Carbon();
        return $carbon->diffInMinutes($this->created_at->format('Y-m-d H:i:s')) < 1;
    }

}
