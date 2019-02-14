<?php

use App\User;
use App\Company;
use App\Team;
use Illuminate\Database\Seeder;

class ProductionUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = Company::create([
        	'name' => 'Default'
        ]);

        $default_team = $company->teams()->create([
                'name' => $company->name.' Default Team',
                'company_id' => $company->id,
                'slug' => 'default-'.$company->id,
                'description' => 'This is the default team for '. $company->name
        ]);

        $company->teams()->create([
            'name' => $company->name.' Client Team',
            'company_id' => $company->id,
            'slug' => 'client-'.$company->id,
            'description' => 'This is the client team for '. $company->name
        ]);

        $company->teams()->create([
            'name' => $company->name.' Clients Staffs',
            'company_id' => $company->id,
            'slug' => 'client-staffs-'.$company->id,
            'description' => 'This is the clients staffs team for '. $company->name
        ]);

        $user = new User();

        $userBen = $user->create(
        	[
                'username' => env('ADMIN_USERNAME', 'ross123'),
                'first_name' => 'Ross',
        		'last_name' => 'Mosqueda',
        		'email' => env('ADMIN_EMAIL', 'ross.buzzooka@gmail.com'),
                'image_url' => 'img/members/alfred.png',
        		'password' => bcrypt(env('ADMIN_PASSWORD', '12345')),
        		'job_title' => 'Administrator',
        		'telephone' => '1234567898'
        	]
        );

        $default_team->members()->attach($userBen);

        $userBen->assignRole('admin');
    }
}
