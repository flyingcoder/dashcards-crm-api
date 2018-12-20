<?php

namespace Tests\Feature;

use App\User;
use App\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvoiceTest extends TestCase
{
    public function testInvoiceIndex()
    {
        $this->withoutExceptionHandling();

        $user = User::all()->first();

        $data = [
            'title' => 'THis is my invoice',
            'terms' => 'adsfs'
        ];

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->post('api/invoice', $data);

        dd($response->content());
        $response->assertStatus(200);
    }

    public function testProjectInvoice()
    {
        $this->withoutExceptionHandling();

        $user = User::all()->first();

        $model = Project::all()->last();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/projects/'.$model->id.'/invoice');

        dd($response->content());
        $response->assertStatus(200);
    }

    public function testStoreProjectInvoice()
    {
        $this->withoutExceptionHandling();

        $user = User::all()->first();

        $model = Project::all()->last();

        $data = [
            'title' => 'THis is my invoice',
            'terms' => 'adsfs'
        ];

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->post('api/projects/'.$model->id.'/invoice', $data);

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testInvoice()
    {
    	$user = User::all()->first();

    	$response = $this->actingAs($user, 'api')
    					 ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
    					 ->get('api/invoice/1');
		
		//dd($response->content());
    	$response->assertStatus(200);
    }
}
