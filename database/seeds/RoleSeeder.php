<?php

use App\Company;
use Illuminate\Database\Seeder;
use Kodeine\Acl\Models\Eloquent\Role;
use Kodeine\Acl\Models\Eloquent\Permission;

class RoleSeeder extends Seeder
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

    	$permission = new Permission();

        $permission->create(
        	[
        		'company_id' => 1,
        		'name' => 'user',
			    'slug' => [
			        'create'     => true,
			        'view'       => true,
			        'update'     => true,
			        'delete'     => true
			    ],
			    'description' => 'My Project Files'
	        ]);
        
        $permission->create(
	        [
	        	'company_id' => 1,
        		'name' => 'project_details',
			    'slug' => [
			        'create'     => true,
			        'view'       => true,
			        'update'     => true,
			        'delete'     => true
			    ],
			    'description' => 'My Project Details'
	        ]);
        $permission->create(
	        [
	        	'company_id' => 1,
        		'name' => 'project_milestones',
			    'slug' => [
			        'create'     => true,
			        'view'       => true,
			        'update'     => true,
			        'delete'     => true
			    ],
			    'description' => 'My Project Milestones'
	        ]);
        $permission->create(
	        [
	        	'company_id' => 1,
        		'name' => 'project_own_tasks',
			    'slug' => [
			        'create'     => true,
			        'view'       => true,
			        'update'     => true,
			        'delete'     => true
			    ],
			    'description' => 'My Project Own Task'
	        ]);
        $permission->create(
	        [
	        	'company_id' => 1,
        		'name' => 'project_other_tasks',
			    'slug' => [
			        'create'     => true,
			        'view'       => true,
			        'update'     => true,
			        'delete'     => true
			    ],
			    'description' => 'My Project Other Task'
	        ]);

    	$role = new Role();

    	$company = Company::findOrfail(1);

		$roleAdmin = $role->create(
			[
			    'name' => 'Administrator',
			    'slug' => 'admin',
				'description' => 'manage administration privileges',
			]
		);

		$roleClient = $role->create(
			[
			    'name' => 'Client',
			    'slug' => 'client',
				'description' => 'Client privileges',
			]
		);

		$roleAgent = $role->create(
			[
			    'name' => 'Agent',
			    'slug' => 'agent',
				'description' => 'manage agent privileges',
			]
		);

		$roleManager = $role->create(
			[
			    'name' => 'Manager',
			    'slug' => 'manager',
				'description' => 'manage a team privileges',
			]
		);

		$roleProvider = $role->create(
			[
			    'name' => 'Provider',
			    'slug' => 'provider',
				'description' => 'provider privileges',
			]
		);

		$company->roles()->attach([ 
			$roleAdmin->id, 
			$roleProvider->id, 
			$roleManager->id,
			$roleAgent->id,
			$roleClient->id,
		]);


    }
}
