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
    public function testGroups()
    {
        $this->withoutExceptionHandling();

    	$user = User::find(1);

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
     
    public function testStore()
    {
        
        $this->withoutExceptionHandling();

        $user = User::findOrFail(1);

        $data = [
            'name' => 'Sales Agent'
        ];

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->post('api/groups', $data);

        //dd($response->content());
        $response->assertStatus(201);
        //$this->assertTrue(true);
    }*/

     /**
     * A basic test example.
     *
     * @return void
     
    public function testUpdate()
    {
       $this->withoutExceptionHandling();

        $user = User::findOrFail(1);

        $modelput = Role::latest()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->put('api/groups/'.$modelput->id.'/permission');

        //dd($response->content());
        $response->assertStatus(200);
    }*/

     /**
     * A basic test example.
     *
     * @return void
     */
    public function testUpdate()
    {
       $this->withoutExceptionHandling();

        $user = User::findOrFail(1);

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

        $user = User::findOrFail(1);

        $modeldelete = Role::latest()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->delete('api/groups/'.$modeldelete->id);

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testCompanyTeams()
    {
        $user = User::find(1);

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

        $user = User::find(1);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/groups?search=adm');

        //dd($response->content());
        $response->assertStatus(200);
    }
}
