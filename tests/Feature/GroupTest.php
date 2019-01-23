<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Kodeine\Acl\Models\Eloquent\Role;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GroupTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStore()
    {
        
        $this->withoutExceptionHandling();

        $user = User::findOrFail(1);

        $data = [
            'name' => 'Sales Agent',
            'description' => 'taetasdfsa',
            'permission_id' => [1,2]
        ];

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->post('api/groups', $data);

        dd($response->content());
        $response->assertStatus(201);
        //$this->assertTrue(true);
    }

    public function testAssign()
    {
        $this->withoutExceptionHandling();

        $user = User::all()->first();

        $model = Role::latest()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/groups/'.$model->id.'/permission');

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testGroups()
    {
        $this->withoutExceptionHandling();

    	$user = User::all()->first();

    	$response = $this->actingAs($user, 'api')
    					 ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
    					 ->get('api/groups');

    	//dd($response->content());
    	$response->assertStatus(200);
    }

     /**
     * A basic test example.
     *
     * @return void
     */
    public function testUpdate()
    {
       $this->withoutExceptionHandling();

        $user = User::all()->first();

        $modelput = Role::latest()->first();

        $data = [
            'name' => 'Sales Marketing'
        ];

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->put('api/groups/'.$modelput->id , $data);

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testDelete()
    {
       $this->withoutExceptionHandling();

        $user = User::all()->first();

        $modeldelete = Role::latest()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->delete('api/groups/'.$modeldelete->id);

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testCompanyTeams()
    {
        $user = User::all()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/company/teams');

        //dd($response->content());
        $response->assertStatus(200);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGroupsWithSearch()
    {
        $this->withoutExceptionHandling();

        $user = User::all()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/groups?search=adm');

        //dd($response->content());
        $response->assertStatus(200);
    }
}
