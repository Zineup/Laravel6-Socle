<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CRUD\City;
use Faker\Generator as Faker;

$factory->define(City::class, function (Faker $faker) {
    return [
        'name' => $faker->city,
        'postal_code' => $faker->randomNumber('4').$faker->randomLetter(2),
        'population' => $faker->randomNumber('9'),
        'region' => $faker->word(8),
        'country' => $faker->country,
    ];
});
