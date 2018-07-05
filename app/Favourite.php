<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    use RecordsActivity;
    //
//    protected $with = ['favourited'];

    protected $guarded = [];

    public function favourited()
    {
        return $this->morphTo();
    }
}
