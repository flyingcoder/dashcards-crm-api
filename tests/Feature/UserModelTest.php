<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserModelTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUser()
    {
        $user = User::find(1);

    	$response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
    					 ->get('api/user');

    	//dd($response->exception->validator->messages());
    	$response->assertStatus(200);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testProjects()
    {
        $user = User::find(1);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/user/projects');

        dd($response->content());
        $response->assertStatus(200);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testTask()
    {
        $user = User::find(1);

    	$response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
    					 ->get('api/user/tasks');

    	//dd($response->exception->validator->messages());
    	$response->assertStatus(200);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCountTask()
    {
        $user = User::find(1);

    	$response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
    					 ->get('api/user/tasks/count');

    	//dd($response->exception->validator->messages());
    	//dd($response->content());
    	$response->assertStatus(200);
    }
}
