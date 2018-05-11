<?php

use Faker\Generator as Faker;

$factory->define(App\Milestone::class, function (Faker $faker) {
    return [
        'project_id' => function () {
       		return factory(App\Project::class)->create()->id;
       	},
       	'title' => $faker->realText($maxNbChars = 50, $indexSize = 1),
       	'status' => $faker->randomElement(['in progress', 'done', 'late']),
       	'started_at' => $faker->date($format = 'Y-m-d', $max = 'now'),
       	'end_at' => $faker->date($format = 'Y-m-d', $max = 'now')
    ];
});
