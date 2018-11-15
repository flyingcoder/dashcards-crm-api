<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\Project;
use App\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectModelTest extends TestCase
{
    public function testProjects()
    {
        $this->withoutExceptionHandling();

        $user = User::all()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/projects?page=1&search=ross&sort=&per_page=5');

        //dd($response->content());
        $response->assertStatus(200);
    }


    public function testCreateProject()
    {
        $this->withoutExceptionHandling();

        $user = User::all()->first();
        
        $data = [
            'title' => 'Test',
            'client_id' => User::where('job_title','Client')->first()->id,
            'service_id' => Service::latest()->first()->id,
            'start_at' => '2018-12-19',
            'end_at' => '2018-12-19',
            'location' => 'required',
            'description' => 'required',
            'comment' => 'test comment',
            'members' => [
                User::latest()->first()->id,
                User::latest()->first()->id - 1
            ]
        ];
        
        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->post('api/projects', $data);

        dd($response->content());
        $response->assertStatus(200);
    }

    public function testUpdateProject()
    {
        $this->withoutExceptionHandling();

        $user = User::all()->first();

        $model = Project::latest()->first();

        $data = [
            'title' => 'Test',
            'client_id' => User::where('job_title','Client')->first()->id,
            'service_id' => 1,
            'start_at' => '2018-12-19',
            'end_at' => '2018-12-19',
            'location' => 'required',
            'description' => 'required'
        ];
        
        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->put('api/projects/'.$model->id , $data);

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testProjectProgress()
    {
        $project = Project::findOrFail(1);

        //dd($project->progress());
        $this->assertTrue(is_numeric($project->progress()));
    }


    public function testTotalTime()
    {
        $user = User::all()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/projects/1/timer');

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testProjectTasks()
    {
    	$user = User::all()->first();

    	$response = $this->actingAs($user, 'api')
    					 ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
    					 ->get('api/projects/1/tasks');

    	//dd($response->content());
    	$response->assertStatus(200);
    }


    public function testProject()
    {
    	$user = User::all()->first();

    	$response = $this->actingAs($user, 'api')
    					 ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
    					 ->get('api/projects/1');

    	//dd($response->content());
    	$response->assertStatus(200);
    }

    public function testProjectActivity()
    {
    	$user = User::all()->first();

    	$response = $this->actingAs($user, 'api')
    					 ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
    					 ->get('api/projects/1/timeline');

    	//dd($response->content());				 
    	$response->assertStatus(200);
    }

    public function testProjectMedia()
    {
        $user = User::all()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/projects/1/files');

        //dd($response->content());              
        $response->assertStatus(200);
    }

}
