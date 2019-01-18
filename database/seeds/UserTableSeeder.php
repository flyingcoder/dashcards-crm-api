<?php
use App\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user = new User();
        $userBen = $user->create(
        	[
                'username' => env('ADMIN_USERNAME', 'ross12345'),
                'first_name' => 'Ross',
        		'last_name' => 'Mosqueda',
        		'email' => env('ADMIN_EMAIL', 'ross.buzzooka@gmail.com'),
                'image_url' => 'img/members/alfred.png',
        		'password' => bcrypt(env('ADMIN_PASSWORD', '12345')),
        		'job_title' => 'Administrator',
        		'telephone' => '1234567898'
        	]
        );
        $userBen->assignRole('admin');

        $userdust = $user->create(
        	[
                'username' => 'admin',
                'first_name' => 'Admin',
        		'last_name' => 'Developer',
        		'email' => env('ADMIN_EMAIL', 'dustin@gmail.com'),
                'image_url' => 'img/members/alfred.png',
        		'password' => bcrypt(env('ADMIN_PASSWORD', 'admin')),
        		'job_title' => 'Administrator',
        		'telephone' => '1234567898'
        	]
        );
        $userdust->assignRole('admin');

        $team = App\Team::first();

        $team->members()->attach([1,2]);

        App\Team::create([
            'name' => 'Clients teams',
            'description' => 'Clients team of seed company',
            'company_id' => 1,
            'slug' => 'clients-1'
        ]);

        $userBentong = $user->create(
            [
                'username' => 'alvin2',
                'first_name' => 'Alvin',
                'last_name' => 'Pacot',
                'email' => 'alvin@buzzooka.com',
                'image_url' => 'img/members/alfred.png',
                'password' => bcrypt('alvin2018'),
                'job_title' => 'Client',
                'telephone' => '1234567898'
            ]
        );

        $userBentong->setMeta('company_name', 'Buzzooka');
        $userBentong->setMeta('company_email', 'buzzooka@gmail.com');
        $userBentong->setMeta('company_tel', '228-1434');
        $userBentong->setMeta('status', 'active');

        $userBentong->assignRole('client');

        $team = App\Team::where('company_id', 1)
                        ->where('slug', 'client-1')
                        ->get();

        $userBentong->teams()->attach($team);
    }
}
