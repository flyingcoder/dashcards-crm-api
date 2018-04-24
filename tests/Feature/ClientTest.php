<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientTest extends TestCase
{
    /*
    public function testStoreClients()
    {
        $user = User::find(1);

        $data = [
            'company_name' => 'Alvin',
            'company_email' => 'alvin'.rand(0, 100).'@gmail.com',
            'last_name' => 'Pacot',
            'first_name' => 'Alvin',
            'email' => 'alvin'.rand(0, 100).'@gmail.com',
            'password' => 'testPass',
            'telephone' => '09213123213',
            'status' => 'active',
        ];

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->post('api/clients', $data);

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testUpdateClients()
    {
        $user = User::find(1);

        $data = [
            'company_name' => 'Alvin',
            'company_email' => 'alvin'.rand(0, 100).'@gmail.com',
            'last_name' => 'Pacot the best',
            'first_name' => 'Alvintong',
            'email' => 'alvin'.rand(0, 100).'@gmail.com',
            'password' => 'testPass',
            'telephone' => '09213123213',
            'status' => 'active',
        ];

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->put('api/clients/10', $data);

        //dd($response->content());
        $response->assertStatus(200);
    }*/

    public function testClients()
    {
        $user = User::find(1);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/clients');

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testClientsAll()
    {
        $user = User::find(1);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/clients?all=true');

        //dd($response->content());
        $response->assertStatus(200);
    }
}
