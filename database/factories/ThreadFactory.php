<?php

use Faker\Generator as Faker;

$factory->define(App\Thread::class, function (Faker $faker) {
    return [
        'channel_id'     => function () {
            return factory(App\Channel::class)->create()->id;
        },
        'user_id'   => function () {
            return factory('App\User')->create()->id;
        },
        'title' => $faker->sentence,
        'body'  => $faker->paragraph,

    ];
});

$factory->define(App\Reply::class, function (Faker $faker) {
    return [
        'user_id'       => function () {
            return factory(App\User::class)->create()->id;
        },
        'thread_id'     => function () {
            return factory(App\Thread::class)->create()->id;
        },
        'body'          => $faker->paragraph,

    ];
});