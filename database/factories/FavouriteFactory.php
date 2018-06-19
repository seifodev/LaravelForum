<?php

use Faker\Generator as Faker;

$factory->define(App\Favourite::class, function (Faker $faker) {
    return [
        'user_id'           => function () {
            return factory('App\User')->create()->id;
        },
        'favourited_id'     => 1,
        'favourited_type'   => 'App\Reply'
    ];
});
