<?php

use Faker\Generator as Faker;

$factory->define(App\Task::class, function (Faker $faker) {
    return [
        'milestone_id' => function () {
       		return factory(App\Milestone::class)->create()->id;
       	},
       	'title' => $faker->realText($maxNbChars = 50, $indexSize = 1),
       	'description' => $faker->realText($maxNbChars = 200, $indexSize = 1),
       	'started_at' => $faker->date($format = 'Y-m-d', $max = 'now'),
       	'status' => $faker->randomElement(['open', 'completed', 'behind', 'pending']),
       	'end_at' => $faker->date($format = 'Y-m-d', $max = 'now')
    ];
});
