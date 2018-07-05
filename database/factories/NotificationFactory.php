<?php

use Faker\Generator as Faker;
use Illuminate\Notifications\DatabaseNotification;

$factory->define(DatabaseNotification::class, function (Faker $faker) {
    return [
        'id'                => \Ramsey\Uuid\Uuid::uuid4()->toString(),
        'type'              => 'App\Notifications\ThreadWasUpdated',
        'notifiable_id'     => function () {
            return auth()->id() ?: factory(App\User::class)->id;
        },
        'notifiable_type'   => 'App\User',
        'data'              => ['foo' => 'bar'],
        'read_at'           => null,
    ];
});
