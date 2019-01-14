<?php

use App\User;
use Faker\Generator as Faker;

$factory->define(App\Models\Question::class, function (Faker $faker) {
    return [
        'title' => $title = $faker->sentence(),
        'slug' => str_slug($title),
        'body' => $faker->text,
        'user_id' => function() {
            return User::all()->random();
        },
    ];
});
