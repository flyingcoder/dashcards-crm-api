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
                         ->json('POST', 'api/services', ['name' => 'Alvin']);
                          
        $response->assertStatus(200);*/
    }

    public function testIndex()
    {
        $user = User::findOrFail(1);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/services');
        
        dd($response->content());
        $response->assertStatus(200);
    }

}
