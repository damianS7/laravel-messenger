<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Conversation;
use Faker\Generator as Faker;

$factory->define(Conversation::class, function (Faker $faker) {
    $user_ids = DB::table('contacts')->where('user_id', 1)->pluck('contact_id');
    return [
        'user_a_id' => 1,
        'user_b_id' => $faker->randomElement($user_ids),
    ];
});