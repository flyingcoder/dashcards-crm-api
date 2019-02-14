<?php

use Illuminate\Database\Seeder;

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
        if(env('APP_STAGING')) {
            $this->call(UserTableSeeder::class);
            $this->call(ProjectTableSeeder::class);
        }
        $this->call(ProductionUserSeeder::class);
        $this->call(DashitemSeeder::class);
    }
}
