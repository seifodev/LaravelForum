<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Reply extends Model
{
    use Favouritable;

    protected $guarded = [];

    protected $with = ['owner', 'favourites'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
