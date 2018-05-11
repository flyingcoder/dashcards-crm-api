<?php

use Faker\Generator as Faker;

$factory->define(App\Project::class, function (Faker $faker) {
    return [
       	'service_id' => function () {
       		return factory(App\Service::class)->create()->id;
       	},
       	'title' => $faker->realText($maxNbChars = 50, $indexSize = 1),
       	'description' => $faker->paragraph,
       	'started_at' => $faker->date($format = 'Y-m-d', $max = 'now'),
       	'end_at' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'status' => $faker->randomElement(['Active', 'Hold', 'Closed'])
    ];
});