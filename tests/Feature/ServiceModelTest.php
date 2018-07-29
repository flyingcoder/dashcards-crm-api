<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Service;
use App\User;

class ServiceModelTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
    */
    public function testStore()
    {
        $this->assertTrue(true);
        /*
    	$user = User::findOrFail(1);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->post('api/services', [['name' => 'Alvin'], ['name' => 'Alvintong']]);
        
        //dd($response->content());                 
        $response->assertStatus(200);*/
    }

    /**
     * A basic test example.
     *
     * @return void
    */
    public function testValidate()
    {
        $this->assertTrue(true);
        
        $user = User::findOrFail(1);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->post('api/services/validate', ['name' => 'asdfdsa']);
        
        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testIndexAll()
    {
        $user = User::findOrFail(1);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/services?all=true');
        
        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testIndex()
    {
        $user = User::findOrFail(1);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/services');
        
        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testSearch()
    {
        $this->withoutExceptionHandling();

        $user = User::findOrFail(1);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/services?search=par');
        
        //dd($response->content());
        $response->assertStatus(200);
    }

}
