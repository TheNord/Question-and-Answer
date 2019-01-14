<?php

use App\Models\Question;
use App\Models\Tag;
use Faker\Generator as Faker;

$factory->define(App\Models\QuestionTag::class, function (Faker $faker) {
    return [
        'tag_id' => function() {
            return Tag::all()->random();
        },
        'question_id' => function() {
            return Question::all()->random();
        }
    ];
});
