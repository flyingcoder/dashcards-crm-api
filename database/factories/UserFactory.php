<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'image_url' => $faker->randomElement([
            'img/members/alfred.png',
            'img/members/bruce.png',
            'img/members/jason.png',
            'img/members/selena.png',
            'img/members/tim.png']),
        'job_title' => 'testes',
        'telephone' => 'testes',
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});
