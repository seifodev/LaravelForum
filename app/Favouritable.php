<?php

namespace App;


trait Favouritable
{
    public function favourites()
    {
        return $this->morphMany('App\Favourite', 'favourited');
    }

    public function favourite($userId)
    {
        // TODO:: exists()
        if(!$this->favourites()->whereUserId($userId)->exists())
        {
            $this->favourites()->create(['user_id' => auth()->id()]);
        } else
        {
//            $this->favourites()->whereUserId($userId)->delete();
        }
    }

    public function isFavourited()
    {
        return !! $this->favourites->where('user_id', auth()->id())->count();
    }
}