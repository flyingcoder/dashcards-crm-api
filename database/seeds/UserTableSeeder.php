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
        //team seeder
        factory(App\Team::class)->create();

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
        		'telephone' => '123456789'
        	]
        );
        $userBen->assignRole('admin');

        App\Team::create([
            'name' => 'Clients',
            'description' => 'Clients team of seed company',
            'company_id' => 1,
            'slug' => 'clients'
        ]);

        $userBentong = $user->create(
            [
                'username' => 'alvin',
                'first_name' => 'Alvin',
                'last_name' => 'Pacot',
                'email' => 'alvin@buzzooka.com',
                'image_url' => 'img/members/alfred.png',
                'password' => bcrypt('alvn2018'),
                'job_title' => 'Client',
                'telephone' => '123456789'
            ]
        );

        $userBentong->setMeta('company_name', 'Buzzooka');
        $userBentong->setMeta('company_email', 'buzzooka@gmail.com');
        $userBentong->setMeta('company_tel', '228-1434');
        $userBentong->setMeta('status', 'active');

        $userBentong->assignRole('client');

        $team = App\Team::where('company_id', 1)
                        ->where('slug', 'clients')
                        ->get();

        $userBentong->teams()->attach($team);
    }
}
