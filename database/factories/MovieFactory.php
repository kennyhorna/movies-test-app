<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Movie;
use Faker\Generator as Faker;

$factory->define(Movie::class, function (Faker $faker) {
    return [
        'name'         => $faker->title,
        'release_date' => $faker->date('d/m/Y'),
        'status'       => $faker->boolean,
        'image'        => 'some-file.jpg',
    ];
});

$factory->afterCreating(Movie::class, function (Movie $movie, Faker $faker) {
    $movie->turns()->attach((factory(\App\Models\Turn::class)->create())->id);
});
