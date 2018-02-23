<?php

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
    	$permission = new Permission();

        $permission->create(
        	[
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

		$roleAdmin = $role->create(
			[
			    'name' => 'Administrator',
			    'slug' => 'admin',
				'description' => 'manage administration privileges',
				'company_id' => 1
			]
		);

		$roleClient = $role->create(
			[
			    'name' => 'Client',
			    'slug' => 'client',
				'description' => 'Client privileges',
				'company_id' => 1
			]
		);

		$roleAgent = $role->create(
			[
			    'name' => 'Agent',
			    'slug' => 'agent',
				'description' => 'manage agent privileges',
				'company_id' => 1
			]
		);

		$roleManager = $role->create(
			[
			    'name' => 'Manager',
			    'slug' => 'manager',
				'description' => 'manage a team privileges',
				'company_id' => 1
			]
		);

		$roleProvider = $role->create(
			[
			    'name' => 'Provider',
			    'slug' => 'provider',
				'description' => 'provider privileges',
				'company_id' => 1
			]
		);
    }
}
