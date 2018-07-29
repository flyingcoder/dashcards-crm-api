<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyTest extends TestCase
{
    public function testSettings()
    {
        $user = User::find(1);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/user/company/details');

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testMembers()
    {
        $user = User::find(1);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/company/members');

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testAddSettings()
    {
        $user = User::find(1);

        $data = [
        	'address_line' => "request()->address_line",
            'city' => "request()->city",
            'state' => "request()->state",
            'zip_code' => "request()->zip_code",
            'country' => "request()->country",
            'telephone' => "request()->telephone",
            'from_name' => "request()->from_name",
            'email_signature' => "request()->email_signature"
        ];
        /*
        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->post('api/user/company', $data);

        dd($response->content());
        $response->assertStatus(200);*/
    }
}
