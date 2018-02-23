<?php

use Faker\Generator as Faker;

$factory->define(App\Service::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
        'user_id' => function () {
        	return factory(App\User::class)->create()->id;
        }
    ];
});
