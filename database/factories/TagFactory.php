<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Tag::class, function (Faker $faker) {
    return [
        'name' => $category = $faker->word,
        'slug' => str_slug($category),
    ];
});
