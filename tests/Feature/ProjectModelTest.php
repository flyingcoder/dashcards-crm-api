<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectModelTest extends TestCase
{

    public function testCreateProject()
    {
        $user = User::find(1);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->post('api/projects');

        //dd($response->content());
        $response->assertStatus(200);
    }


    public function testTotalTime()
    {
        $user = User::find(1);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/projects/1/timer');

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testProjectTasks()
    {
    	$user = User::find(1);

    	$response = $this->actingAs($user, 'api')
    					 ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
    					 ->get('api/projects/1/tasks');

    	//dd($response->content());
    	$response->assertStatus(200);
    }


    public function testProject()
    {
    	$user = User::find(1);

    	$response = $this->actingAs($user, 'api')
    					 ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
    					 ->get('api/projects/1');

    	//dd($response->content());
    	$response->assertStatus(200);
    }

    public function testProjectActivity()
    {
    	$user = User::find(1);

    	$response = $this->actingAs($user, 'api')
    					 ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
    					 ->get('api/projects/1/timeline');

    	//dd($response->content());				 
    	$response->assertStatus(200);
    }

    public function testProjectMedia()
    {
        $user = User::find(1);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/projects/1/files');

        //dd($response->content());              
        $response->assertStatus(200);
    }

}
