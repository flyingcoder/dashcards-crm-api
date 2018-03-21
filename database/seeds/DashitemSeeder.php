<?php

use Illuminate\Database\Seeder;

use App\Dashitem;
use App\Dashboard;
use App\Company;

class DashitemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dashboard = new Dashboard();

        $buzzdash = $dashboard->create([
        	'company_id' => 1,
        	'title' => 'Buzz Dashboard',
        	'description' => 'Dashboard Description'
        ]);

        $dashitem = new Dashitem();

        $tasks = $dashitem->create([
        	'name' => 'Tasks',
        	'slug' => 'tasks',
        	'description' => '',
        	'type' => '',
        ]);

        //$buzzdash->dashitems()->attach($tasks, ['order' => 1]);

        $timeline = $dashitem->create([
        	'name' => 'Timeline',
        	'slug' => 'timeline',
        	'description' => '',
        	'type' => '',
        ]);

        //$buzzdash->dashitems()->attach($timeline, ['order' => 2]);

        $client = $dashitem->create([
        	'name' => 'Client',
        	'slug' => 'client',
        	'description' => '',
        	'type' => '',
        ]);

        //$buzzdash->dashitems()->attach($client, ['order' => 3]);

        $timer = $dashitem->create([
        	'name' => 'Timer',
        	'slug' => 'timer',
        	'description' => '',
        	'type' => '',
        ]);

        //$buzzdash->dashitems()->attach($timer, ['order' => 4]);

        $payment = $dashitem->create([
        	'name' => 'Payment',
        	'slug' => 'payment',
        	'description' => '',
        	'type' => '',
        ]);

        //$buzzdash->dashitems()->attach($payment, ['order' => 5]);

        $invoice = $dashitem->create([
        	'name' => 'Invoice',
        	'slug' => 'invoice',
        	'description' => '',
        	'type' => '',
        ]);

        //$buzzdash->dashitems()->attach($invoice, ['order' => 6]);

        $calendar = $dashitem->create([
        	'name' => 'Calendar',
        	'slug' => 'calendar',
        	'description' => '',
        	'type' => '',
        ]);

        //$buzzdash->dashitems()->attach($calendar, ['order' => 7]);

        $passbox = $dashitem->create([
        	'name' => 'Passbox',
        	'slug' => 'passbox',
        	'description' => '',
        	'type' => '',
        ]);

        //$buzzdash->dashitems()->attach($passbox, ['order' => 8]);
    }
}
