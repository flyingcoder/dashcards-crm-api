<?php

use Illuminate\Database\Seeder;
use App\Configuration;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       	$configs = [
        	[
        		'key' => 'allowed_modules',
        		'value' => '["projects","campaign","clients","teams","services","invoices","notes","calendars","forms","timers","connect","chat","reports","signup"]',
        		'type' => 'array'
        	],
            [
                'key' => 'allowed_dashcards',
                'value' => '["tasks","timeline","client","timer","alarm","invoice","calendar"]',
                'type' => 'array'
            ],
            [
                'key' => 'allowed_dashtiles',
                'value' => '["tasks","timeline","client","timer","alarm","invoice","calendar","payment","inbound","calendars","projects","outbound"]',
                'type' => 'array'
            ],
            [
                'key' => 'recaptcha',
                'value' => '{"enabled":false,"key":""}',
                'type' => 'object'
            ],
            [
                'key' => 'meta',
                'value' => '{"name":"DashCards","description":"","image":""}',
                'type' => 'object'
            ],
            [
                'key' => 'connects',
                'value' => '{"google_drive":true,"stripe":true,"paypal":true,"dropbox":true,"google_meet":true,"zoom":true,"seo_profiler":true,"skype":true,"semrush":true,"brightlocal":true,"google_calendar":true,"lastpass":true}',
                'type' => 'object'
            ],
        ];

        
    	Configuration::insert($configs);
    }
}
