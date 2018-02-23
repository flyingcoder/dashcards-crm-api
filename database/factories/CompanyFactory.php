<?php

use Faker\Generator as Faker;

$factory->define(App\Company::class, function (Faker $faker) {
    return [
        'name' => $faker->randomElement(['Google', 'Facebook', 'Linkedin', 'Buzzooka']),
        'email' => $faker->unique()->safeEmail,
        'domain' => '',
        'tag_line' => '',
        'short_description' => '',
        'long_description' => ''
    ];
});
