<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Turn;
use Faker\Generator as Faker;

$factory->define(Turn::class, function (Faker $faker) {
    return [
        'schedule' => $faker->time('H:i'),
    ];
});
