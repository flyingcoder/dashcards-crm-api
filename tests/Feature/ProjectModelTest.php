<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\Project;
use App\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectModelTest extends TestCase
{
    public function testMilestoneImport()
    {
        # code...
    }

    
    public function testProjectMember()
    {
        $this->withoutExceptionHandling();

        $user = User::all()->first();

        $model = Project::all()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/projects/'.$model->id.'/member?sort=&page=1&search=');

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testAssignMember()
    {
        $this->withoutExceptionHandling();

        $user = User::all()->first();

        $model = Project::latest()->first();

        $data = [
            'members_id' => [
                User::latest()->first()->id,
                User::findOrFail(User::latest()->first()->id-1)->id,
                User::findOrFail(User::latest()->first()->id-2)->id,
            ]
        ];

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->post('api/projects/'.$model->id.'/member', $data);

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testRemoveMember()
    {
        $this->withoutExceptionHandling();

        $user = User::all()->first();

        $model = Project::latest()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->delete('api/projects/'.$model->id.'/member/5');

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testProjectMyTasks()
    {
        $this->withoutExceptionHandling();

        $user = User::all()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/projects/1/tasks/mine');

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testProjectTasks()
    {
        $this->withoutExceptionHandling();

        $user = User::all()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/projects/1/tasks');

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testProjectActivity()
    {
        $this->withoutExceptionHandling();

        $user = User::all()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/projects/1/timeline');

        //dd($response->content());              
        $response->assertStatus(200);
    }

    public function testProject()
    {
        $this->withoutExceptionHandling();

        $user = User::all()->first();

        $model = Project::latest()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/projects/5');

        //dd($response->content());
        $response->assertStatus(200);
    }

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

        //dd($response->content());
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
}
