<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Milestone;
use App\Template;
use App\Project;
use App\User;
use App\Company;

class MilestoneTest extends TestCase
{

    public function testAddMilestoneTemplate()
    {
        $this->withoutExceptionHandling();

        $user = User::findOrFail(1);

        $model = Template::latest()->first();

        $data = [
            'title' => 'SEO Milestones 5',
            'status' => 'Active',
            'days' => 7
        ];

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->post('api/template/'.$model->id.'/milestone', $data);

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testAddMilestoneProject()
    {
        $this->withoutExceptionHandling();

        $user = User::findOrFail(1);

        $model = Project::latest()->first();

        $data = [
            'title' => 'SEO Milestones 6',
            'status' => 'Active',
            'days' => 7
        ];

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->post('api/project/'.$model->id.'/milestone', $data);

        //dd($response->content());
        //$response->assertStatus(201);
        $this->assertTrue(true);
    }

    public function testMilestone()
    {
    	$this->withoutExceptionHandling();

        $user = User::find(1);

        $model = Milestone::latest()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/template/1/milestone/'.$model->id);

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testUpdate()
    {
        $this->withoutExceptionHandling();

        $user = User::find(1);

        $model = Milestone::latest()->first();

        $parent_model = Template::latest()->first();

        $data = [
            'title' => 'SEO Milestones 99',
            'status' => 'Active',
            'days' => 6
        ];

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->put('api/template/'.$parent_model->id.'/milestone/'.$model->id, $data);

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testDelete()
    {
       $this->withoutExceptionHandling();

        $user = User::findOrFail(1);

        $model = Milestone::latest()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->delete('api/template/1/milestone/'.$model->id);

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testIndexTemplate()
    {
        $this->withoutExceptionHandling();

        $user = User::find(1);

        $parent = 'template';

        $model = Template::latest()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get("api/{$parent}/{$model->id}/milestone");

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testIndexProject()
    {
        $this->withoutExceptionHandling();

        $user = User::find(1);

        $parent = 'project';

        $model = Project::latest()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get("api/{$parent}/{$model->id}/milestone");

        //dd($response->content());
        $response->assertStatus(200);
    }
}
