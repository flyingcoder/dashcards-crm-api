<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRegister()
    {

    	$data = [
    		'company_name' => 'Alvin',
            'company_email' => 'alvin'.rand(0, 100).'@gmail.com',
            'last_name' => 'Pacot',
            'first_name' => 'Alvin',
            'username' => 'alvintong',
            'email' => 'alvin@gmail.com',
            'password' => 'testPass',
    	];

    	$response = $this->post('api/register/subscriber', $data);
    					 

    	dd($response->content());
    	$response->assertStatus(200);
    }
}
