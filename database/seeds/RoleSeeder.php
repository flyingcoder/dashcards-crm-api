<?php

use App\Company;
use Illuminate\Database\Seeder;
use Kodeine\Acl\Models\Eloquent\Role;
use Kodeine\Acl\Models\Eloquent\Permission;

class RoleSeeder extends Seeder
{
	protected $slug = [
			[
				'create' => false,
				'update' => false,
				'delete' => false
			],
			[
				'update' => false,
				'delete' => false
			],
			[
				'delete' => false
			]
		];

	protected $parentPermission = [
			'hq_files_own',
			'hq_files',
			'hq_project_details',
			'hq_milestones',
			'hq_tasks_own',
			'hq_tasks',
			'hq_client_messages',
			'hq_messages_own',
			'hq_team_messages',
			'hq_invoices',
			'hq_reports',
			'hq_timers_own',
			'hq_timers',
			'hq_members',
			'projects_own',
			'projects',
			'clients',
			'services',
			'responses',
			'forms',
			'users',
			'user_profile',
			'invoices',
			'calendars_own',
			'calendars',
			'messages',
			'client_users',
			'client_profile',
			'template_milestones',
			'template_invoices',
			'timers_own',
			'timers',
			'tasks',
			'tasks_own',
			'settings_group',
			'settings_permission'
		];

	protected $managerPerm = [
    		'hq_files_own' => 4,
			'hq_files' => 1,
			'hq_project_details' => 4,
			'hq_milestones' => 4,
			'hq_tasks_own' => 3,
			'hq_tasks' => 3,
			'hq_client_messages' => 4,
			'hq_messages_own' => 4,
			'hq_team_messages' => 3,
			'hq_reports' => 4,
			'hq_timers_own' => 1,
			'hq_timers' => 1,
			'hq_members' => 4,
			'projects_own' => 3,
			'services' => 4,
			'responses' => 1,
			'forms' => 1,
			'user_profile' => 3,
			'calendars_own' => 4,
			'calendars' => 4,
			'messages' => 4,
			'timers_own' => 1,
			'timers' => 1,
			'tasks' => 3,
			'tasks_own' => 3,
			'settings_group' => 3,
			'settings_permission' => 3
    	];

    protected $memberPerm = [
    		'hq_files_own' => 4,
			'hq_files' => 1,
			'hq_project_details' => 1,
			'hq_milestones' => 1,
			'hq_tasks_own' => 1,
			'hq_client_messages' => 3,
			'hq_messages_own' => 4,
			'hq_team_messages' => 3,
			'hq_reports' => 1,
			'hq_timers_own' => 1,
			'hq_members' => 1,
			'projects_own' => 3,
			'user_profile' => 3,
			'calendars_own' => 4,
			'calendars' => 1,
			'messages' => 4,
			'timers_own' => 1,
			'tasks_own' => 1,
    	];

    protected $clientPerm = [
			'hq_files_own' => 4,
			'hq_files' => 1,
			'hq_project_details' => 1,
			'hq_milestones' => 1,
			'hq_tasks_own' => 1,
			'hq_client_messages' => 3,
			'hq_messages_own' => 4,
			'hq_invoices' => 1,
			'hq_reports' => 1,
			'hq_timers' => 1,
			'hq_members' => 4,
			'projects_own' => 1,
			'invoices' => 1,
			'calendars_own' => 4,
			'messages' => 4,
			'client_users' => 4,
			'client_profile' => 4,
			'tasks_own' => 1,
		];

	protected $roles = [
    		'Admin',
    		'Client',
    		'Manager',
    		'Member'
    	];


    public function run()
    {
    	$this->createRole();

    	$this->createParentPermission();

    	$this->createChildPermission('manager', $this->managerPerm);

    	$this->createChildPermission('member', $this->memberPerm);

    	$this->createChildPermission('client', $this->clientPerm);
    	
    }

    public function createRole()
    {
    	$role = new Role();

    	foreach ($this->roles as $value) {
    		$role->create(
				[
					'company_id' => 0,
				    'name' => $value,
				    'slug' => strtolower($value),
					'description' => env('APP_NAME') .' '. $value .' Privileges',
				]
			);
    	}
    }

    public function createParentPermission()
    {
    	foreach ($this->parentPermission as $value) {

    		$permission = new Permission();

    		$permission->create(
		        [
		        	'company_id' => 0,
	        		'name' => $value,
				    'slug' => [
				        'create'     => true,
				        'view'       => true,
				        'update'     => true,
				        'delete'     => true
				    ],
				    'description' => env('APP_NAME').' Default Permissions'
		        ]);
    	}
    }

    public function createChildPermission($role, $rolePermission)
    {
    	$permission = new Permission();

    	foreach ($rolePermission as $key => $value) {

    		$parentPerm = Permission::where('name', $key)->first();

    		if(is_null($parentPerm)) 
    			dd($key);

    		$roleModel = Role::where('slug', $role)->first();

    		if($value == 4) {
    			$roleModel->assignPermission($key);
    			continue;
    		}

    		$perm = $permission->create(
			        	[
			        		'company_id' => 0,
			        		'name' => $key.'.'.$role,
						    'slug' => $this->slug[$value-1],
						    'inherit_id' => $parentPerm->getKey(),
						    'description' => env('APP_NAME').' Default Permissions'
				        ]);

			$roleModel->assignPermission($perm->id);

    	}
    }
}
