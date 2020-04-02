<?php

use App\EventType;
use Illuminate\Database\Seeder;

class EventTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   //supported color
        /*gray, red, orange, yellow, green, teal, blue, indigo, purple, pink*/
        $types = [
        	[
        		'name' => 'Default',
        		'company_id' => null,
        		'created_by' => null,
        		'properties' => [ 'color' => 'blue'],
        		'is_public'  => 1,
        		'created_at' => now()->format('Y-m-d H:i:s')
        	],
        	[
        		'name' => 'Meetings',
        		'company_id' => null,
        		'created_by' => null,
        		'properties' => [ 'color' => 'red'],
        		'is_public'  => 1,
        		'created_at' => now()->format('Y-m-d H:i:s')
        	],
        	[
        		'name' => 'Demo',
        		'company_id' => null,
        		'created_by' => null,
        		'properties' => [ 'color' => 'purple'],
        		'is_public'  => 1,
        		'created_at' => now()->format('Y-m-d H:i:s')
        	],
            [
                'name' => 'Occasion',
                'company_id' => null,
                'created_by' => null,
                'properties' => [ 'color' => 'teal'],
                'is_public'  => 1,
                'created_at' => now()->format('Y-m-d H:i:s')
            ],
        	[
        		'name' => 'Others',
        		'company_id' => null,
        		'created_by' => null,
        		'properties' => [ 'color' => 'pink'],
        		'is_public'  => 1,
        		'created_at' => now()->format('Y-m-d H:i:s')
        	],
        ];

        foreach ($types as $key => $type) {
        	EventType::create($type);
        }
    }
}
