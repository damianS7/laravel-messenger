<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Message;
use Faker\Generator as Faker;

$factory->define(Message::class, function (Faker $faker) {
    $conversation_ids = DB::table('conversations')->where('user_a_id', 1)->pluck('id');
    
    return [
        'conversation_id' => $faker->randomElement($conversation_ids),
        'author_id' => 1,
        'content' => $faker->text,
        'sent_at' => $faker->dateTime()
    ];
});