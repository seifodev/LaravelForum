<?php

namespace App;


trait Favouritable
{

    public static function bootFavouritable()
    {
        static::deleting(function ($model) {
            $model->favourites()->get()->each->delete();
        });
    }

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
        }
    }

    public function unfavourite()
    {
        $this->favourites()->where('user_id', auth()->id())->get()->each->delete();
    }

    public function isFavourited()
    {
        return !! $this->favourites->where('user_id', auth()->id())->count();
    }

    public function getIsFavouritedAttribute()
    {
        return $this->isFavourited();
    }
}