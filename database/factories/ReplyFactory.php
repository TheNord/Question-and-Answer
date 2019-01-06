<?php

use App\Models\Question;
use App\User;
use Faker\Generator as Faker;

$factory->define(App\Models\Reply::class, function (Faker $faker) {
    return [
        'body' => $faker->text,
        'question_id' => function() {
            return Question::all()->random();
        },
        'user_id' => function() {
            return User::all()->random();
        },
    ];
});
