<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleTest extends TestCase
{

    public function testGroupMembers()
    {
    	$user = User::find(1);

    	$response = $this->actingAs($user, 'api')
    					 ->get('api/groups/1/members');
    	//dd($response);
    	$response->assertStatus(200);
    }
    /*
    public function testGroupStore()
    {
    	$user = User::find(1);

    	$role = \App\Group::findOrFail(1);

    	//dd($role->test());

    	$data = ['name' => 'This role is for admin'];

    	$response = $this->actingAs($user, 'api')
    					 ->post('api/groups', $data);

    	//dd($response);
    	$response->assertStatus(200);
    }*/
}
