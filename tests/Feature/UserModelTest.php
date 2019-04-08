<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserModelTest extends TestCase
{
    public function testCountProject()
    {
        $this->withoutExceptionHandling();

        $user = User::find(1);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->delete('api/clients/3');

        //dd($response->exception->validator->messages());
        dd($response->content());
        $response->assertStatus(200);
    }

    public function testLogout()
    {
        $this->withoutExceptionHandling();

        $user = User::all()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/logout');

        //dd($response->content());
        $response->assertStatus(200);

    }
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRegister()
    {
        $this->withoutExceptionHandling();

        $user = User::all()->first();

        $data = [
            'company_email' => 'test@gmail.com',
            'company_name' => 'Test',
            'email' => 'test2019@gmail.com',
            'first_name' => 'Testing',
            'last_name' => 'Beta',
            'password' => 'thisisit'
        ];

        $response = $this->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->post('api/register', $data);

        //dd($response->content());
        $response->assertStatus(200);
    }

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

        //dd($response->content());
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
