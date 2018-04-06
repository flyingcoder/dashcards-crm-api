<?php

use Faker\Generator as Faker;

$factory->define(App\Project::class, function (Faker $faker) {
    return [
       	'service_id' => function () {
       		return factory(App\Service::class)->create()->id;
       	},
<<<<<<< HEAD
		'company_id' => 1,
		'title' => $faker->realText($maxNbChars = 50, $indexSize = 1),
=======
        'company_id' => 1,
       	'title' => $faker->title,
>>>>>>> 1e2f5e1011437c53c35fee60faf3b7aef9a27dd3
       	'description' => $faker->paragraph,
       	'started_at' => $faker->date($format = 'Y-m-d', $max = 'now'),
       	'end_at' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'status' => $faker->randomElement(['Active', 'Hold', 'Closed'])
    ];
});