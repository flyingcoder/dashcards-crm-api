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

        $usersPermission = $permission->create(
        	[
        		'company_id' => 0,
        		'name' => 'users',
			    'slug' => [
			        'create'     => true,
			        'view'       => true,
			        'update'     => true,
			        'delete'     => true
			    ],
			    'description' => 'Users Permissions'
	        ]);

        $managerPermission = $permission->create(
        	[
        		'company_id' => 0,
        		'name' => 'users.manager',
			    'slug' => [
			        'delete'     => false
			    ],
			    'inherit_id' => $usersPermission->getKey(),
			    'description' => 'Users Manager Permissions'
	        ]);

        $permission->create(
        	[
        		'company_id' => 0,
        		'name' => 'hq_files_own',
			    'slug' => [
			        'create'     => true,
			        'view'       => true,
			        'update'     => true,
			        'delete'     => true
			    ],
			    'description' => 'Project Own Files'
	        ]);
        
        $permission->create(
	        [
	        	'company_id' => 0,
        		'name' => 'hq_project_details',
			    'slug' => [
			        'create'     => true,
			        'view'       => true,
			        'update'     => true,
			        'delete'     => true
			    ],
			    'description' => 'Project Details'
	        ]);

        $permission->create(
	        [
	        	'company_id' => 0,
        		'name' => 'hq_milestones',
			    'slug' => [
			        'create'     => true,
			        'view'       => true,
			        'update'     => true,
			        'delete'     => true
			    ],
			    'description' => 'Project Milestones'
	        ]);

        $permission->create(
	        [
	        	'company_id' => 0,
        		'name' => 'hq_tasks_own',
			    'slug' => [
			        'create'     => true,
			        'view'       => true,
			        'update'     => true,
			        'delete'     => true
			    ],
			    'description' => 'Project Own Task'
	        ]);
        
        $permission->create(
	        [
	        	'company_id' => 1,
        		'name' => 'hq_tasks',
			    'slug' => [
			        'create'     => true,
			        'view'       => true,
			        'update'     => true,
			        'delete'     => true
			    ],
			    'description' => 'Project Other Task'
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

		$roleManager = $role->create(
			[
			    'name' => 'Manager',
			    'slug' => 'manager',
				'description' => 'manage a team privileges',
			]
		);

		$roleAgent = $role->create(
			[
			    'name' => 'Member',
			    'slug' => 'agent',
				'description' => 'manage member privileges',
			]
		);

		$company->roles()->attach([ 
			$roleAdmin->id,
			$roleManager->id,
			$roleAgent->id,
			$roleClient->id,
		]);


    }
}
