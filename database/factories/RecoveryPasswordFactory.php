<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\RecoveryPassword;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(RecoveryPassword::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class)
    ];
});

$factory->state(RecoveryPassword::class, 'expired', [
    'created_at' => Carbon::now()->subHour(25)
]);
