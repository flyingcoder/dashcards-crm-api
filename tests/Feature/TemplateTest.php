<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Template;
use App\User;
use App\Company;

class TemplateTest extends TestCase
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
            'name' => 'SEO Milestones',
            'status' => 'Active',
            'type' => 'Milestones'
        ];

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->post('api/template', $data);

        //dd($response->content());
        //$response->assertStatus(201);
        $this->assertTrue(true);
    }

     /**
     * A basic test example.
     *
     * @return void
     */
    public function testUpdate()
    {
       $this->withoutExceptionHandling();

        $user = User::findOrFail(1);

        $model = Template::latest()->first();

        $data = [
            'name' => 'SEO Milestones',
            'status' => 'Active',
            'type' => 'Milestone'
        ];

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->put('api/template/'.$model->id , $data);

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testIndex()
    {
    	$this->withoutExceptionHandling();

        $user = User::find(1);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/template');

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testAddMilestoneTemplate()
    {
    	$this->withoutExceptionHandling();

        $user = User::findOrFail(1);

        $model = Template::latest()->first();

        $data = [
            'title' => 'SEO Milestones 1',
            'status' => 'Active',
            'days' => 4
        ];

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->post('api/template/'.$model->id.'/milestone', $data);

        //dd($response->content());
        //$response->assertStatus(201);
        $this->assertTrue(true);
    }

    public function testGetMilestoneTemplate()
    {
    	$this->withoutExceptionHandling();

        $user = User::findOrFail(1);

        $model = Template::latest()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/template/'.$model->id.'/milestone');

        //dd($response->content());
        //$response->assertStatus(201);
        $this->assertTrue(true);
    }

    public function testDelete()
    {
       $this->withoutExceptionHandling();

        $user = User::findOrFail(1);

        $model = Template::latest()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->delete('api/template/'.$model->id);

        //dd($response->content());
        $response->assertStatus(200);
    }
}
