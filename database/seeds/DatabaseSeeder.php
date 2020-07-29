<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *  A project has members. Only members attach to a project can have tasks.
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
//        if(env('APP_STAGING')) {
//            $this->call(UserTableSeeder::class);
//            $this->call(ProjectTableSeeder::class);
//        }
//        $this->call(ProductionUserSeeder::class);
        $this->call(DashitemSeeder::class);
        $this->call(NewDashItemSeeder::class);
        $this->call(ConfigurationSeeder::class);
        $this->call(DefaultInvoiceTemplates::class);
        $this->call(EventTypesSeeder::class);

        Artisan::call('passport:install');
    }
}
