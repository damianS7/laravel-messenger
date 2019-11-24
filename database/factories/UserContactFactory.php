<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use App\UserContact;

$factory->define(UserContact::class, function (Faker $faker) {
    $user_ids = DB::table('users')->pluck('id');
    return [
        'user_id' => 1,
        'contact_id' => $faker->randomElement($user_ids),
    ];
});