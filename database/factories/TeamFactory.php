<?php

use Faker\Generator as Faker;

$factory->define(App\Team::class, function (Faker $faker) {
    return [
        'name' => $faker->randomElement(['Red Team', 'Blue Team', 'Design Team', 'Buzzooka Team']),
        'company_id' => function () {
        	return factory(App\Company::class)->create()->id;
        },
        'description' => $faker->realText($maxNbChars = 200, $indexSize = 1)
    ];
});
