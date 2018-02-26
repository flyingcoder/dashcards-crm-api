<?php

use Faker\Generator as Faker;

$factory->define(App\Invoice::class, function (Faker $faker) {
    return [
    	'user_id' => function (){
    		return App\User::orderByRaw("RAND()")->first();
    	},
        'title' => $faker->realText($maxNbChars = 50, $indexSize = 1), 
        'name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'address' => $faker->streetAddress, 
        'city' => $faker->city, 
        'state' => $faker->state, 
        'phone' => $faker->phoneNumber, 
        'zip_code' => $faker->postcode, 
        'email' => $faker->email, 
        'description' => $faker->realText($maxNbChars = 200, $indexSize = 1), 
        'rate' => rand(0, 1000), 
        'tax' => rand(0, 50), 
        'quantity' => rand(0, 10), 
        'billed_date' => $faker->date($format = 'Y-m-d', $max = 'now'), 
        'due_date' => $faker->date($format = 'Y-m-d', $max = 'now'), 
        'notes' => null,
        'other_info' => null
    ];
});
