<?php

use Illuminate\Database\Seeder;

use App\Dashitem;

class NewDashItemSeeder extends Seeder
{
    public function run()
    {
    	$to_be_added = ['alarm'];

        $dashItems = Dashitem::whereIn('slug', $to_be_added)->pluck('slug')->toArray();

    	foreach ($to_be_added as $key => $slug) {
    		if (!in_array($slug, $dashItems)) {	
    			Dashitem::create([
    					'name' => ucwords($slug), 
    					'slug' => trim($slug)
    				]);
    		}
    	}
    }
}
