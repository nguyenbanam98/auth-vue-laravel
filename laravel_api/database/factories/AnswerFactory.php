<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Answer;
use Faker\Generator as Faker;

$factory->define(Answer::class, function (Faker $faker) {
    return [

        'content' => $faker->text($maxNbChars = 100),
        'correct' => $faker->boolean,

    ];
});
