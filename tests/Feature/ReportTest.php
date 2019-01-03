<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportTest extends TestCase
{
	public function testProjectReports()
    {
    	$this->withoutExceptionHandling();

    	$user = User::all()->first();

    	$response = $this->actingAs($user, 'api')
    					 ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
    					 ->get('api/projects/1/report');
    	
    	dd($response->content());
    	$response->assertStatus(200);
    }

	public function testCreateProjectReports()
    {
    	$this->withoutExceptionHandling();

    	$user = User::all()->first();

    	$data = [
    		'title' => 'SEO reports',
    		'description' => 'This is for SEO reports',
    		'url' => 'https://www.facebook.com/reports'
    	];

    	$response = $this->actingAs($user, 'api')
    					 ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
    					 ->post('api/projects/1/report', $data);

    	//dd($response->content());
    	$response->assertStatus(200);
    }

    public function testReports()
    {
    	$this->withoutExceptionHandling();

    	$user = User::all()->first();

    	$response = $this->actingAs($user, 'api')
    					 ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
    					 ->get('api/report');
    	
    	//dd($response->content());
    	$response->assertStatus(200);
    }

    public function testCreateReports()
    {
    	$this->withoutExceptionHandling();

    	$user = User::all()->first();

    	$data = [
    		'title' => 'SEO reports',
    		'description' => 'This is for SEO reports',
    		'url' => 'https://www.facebook.com/reports'
    	];

    	$response = $this->actingAs($user, 'api')
    					 ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
    					 ->post('api/report', $data);

    	//dd($response->content());
    	$response->assertStatus(200);
    }

    public function testUpdateReports()
    {
    	$this->withoutExceptionHandling();

    	$user = User::all()->first();

    	$data = [
    		'title' => 'SEO report',
    		'description' => 'This is for SEO reports',
    		'url' => 'https://www.facebook.com/reports'
    	];

    	$response = $this->actingAs($user, 'api')
    					 ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
    					 ->put('api/report', $data);

    	//dd($response->content());
    	$response->assertStatus(200);
    }
}
