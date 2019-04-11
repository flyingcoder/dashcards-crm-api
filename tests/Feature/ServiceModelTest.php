<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Service;
use App\User;

class ServiceModelTest extends TestCase
{
    public function testStore()
    {
        $this->withoutExceptionHandling();

        $this->assertTrue(true);
        
        $user = User::findOrFail(1);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->post('api/services', [['name' => 'Alvin'], ['name' => 'Alvintong']]);
        
        dd($response->content());                 
        $response->assertStatus(200);
    }

    public function testIndexWithParams()
    {
         $this->withoutExceptionHandling();

        $user = User::findOrFail(1);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/services?per_page=5&search=&sort=service_name|asc');
        
        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testIndex()
    {
         $this->withoutExceptionHandling();

        $user = User::findOrFail(1);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/services');
        
        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testBulkDelete()
    {
        $this->withoutExceptionHandling();

        $user = User::findOrFail(1);

        $data = [
            'ids' => [
                Service::latest()->first()->id,
                Service::first()->id
            ]
        ];

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->delete('api/services', $data);
        
        //dd($response->content());
        $response->assertStatus(200);
    }

    /**
     * A basic test example.
     *
     * @return void
    */
    public function testValidate()
    {
        
        $user = User::findOrFail(1);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->post('api/services/validate', ['name' => 'asdfdsa']);
        
        //dd($response->content());
        $response->assertStatus(200);
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
        
        $modelput = Service::latest()->first();

        $data = [
            'name' => 'Market Services '.rand(0,10)
        ];

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->put('api/services/'.$modelput->id , $data);
        
        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testDelete()
    {
       $this->withoutExceptionHandling();

        $user = User::findOrFail(1);

        $modeldelete = Service::latest()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->delete('api/services/'.$modeldelete->id);

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
