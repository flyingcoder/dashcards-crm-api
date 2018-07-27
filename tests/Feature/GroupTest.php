<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
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
     */
    public function testGroupsWithSearch()
    {
        $this->withoutExceptionHandling();

        $user = User::find(1);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/groups?search=adm');

        dd($response->content());
        $response->assertStatus(200);
    }
}
