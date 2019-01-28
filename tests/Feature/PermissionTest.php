<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Kodeine\Acl\Models\Eloquent\Role;
use Kodeine\Acl\Models\Eloquent\Permission;

class PermissionTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndex()
    {
       $this->withoutExceptionHandling();

        $user = User::findOrFail(1);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/permission?page=1&search=&sort=');

        dd($response->content());
        $response->assertStatus(200);
    }

    /*
     * A basic test example.
     *
     * @return void
     */
    public function testStore()
    {
       $this->withoutExceptionHandling();

        $user = User::findOrFail(1);

        $data = [ 
            'name'        => 'project_'.rand(),
            'slug'        => [          // pass an array of permissions.
                'create'     => true,
                'view'       => true,
                'update'     => true,
                'delete'     => true,
            ],
            'description' => 'project task permissions'
        ];

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->post('api/permission', $data);

        //dd($response->content());
        $response->assertStatus(201);
        //$this->assertTrue(true);
    }

	

    /**
     * A basic test example.
     *
     * @return void
   	 */
    public function testUpdate()
    {
       $this->withoutExceptionHandling();

        $user = User::findOrFail(1);

        $model = Permission::latest()->first();

        $data = [ 
		    'slug'        => [          // pass an array of permissions.
		        'create'     => true,
		        'view'       => true,
		        'update'     => false,
		        'delete'     => false,
		    ]
		];

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->put('api/permission/'.$model->id, $data);

        //dd($response->content());
        $response->assertStatus(200);
    }

    /**
     * A basic test example.
     *
     * @return void
   	 */
    public function testDelete()
    {
       $this->withoutExceptionHandling();

        $user = User::findOrFail(1);

        $model = Permission::latest()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->delete('api/permission/'.$model->id);

        //dd($response->content());
        $response->assertStatus(200);
    }

     /**
     * A basic test example.
     *
     * @return void
     */
    public function testPermissions()
    {
       $this->withoutExceptionHandling();

        $user = User::findOrFail(1);

        $model = Role::latest()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/groups/'.$model->id.'/permission');

        //dd($response->content());
        $response->assertStatus(200);
    }
}
