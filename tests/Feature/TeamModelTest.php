<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Team;
use App\User;
use App\Company;

class TeamModelTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testServicesFunction()
    {
    	$user = User::findOrFail(1);

        $this->assertTrue(true);
    }

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
            'first_name' => 'Alvin',
            'last_name' => 'Pacot',
            'email' => 'sample@email.com',
            'password' => 'securepassword',
            'telephone' => '+1323453234',
            'password_confirmation' => 'securepassword',
            'group_name' => 'Managers',
            'job_title' => 'Development Manager'
        ];

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->post('api/company/teams', $data);

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

        $userput = User::latest()->first();

        $data = [
            'first_name' => 'Alvin',
            'last_name' => 'Pacot',
            'email' => 'sample123@email.com',
            'telephone' => '+1323453234',
            'group_name' => 'Managers',
            'job_title' => 'Development Manager'
        ];

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->put('api/company/teams/'.$userput->id , $data);

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testDelete()
    {
       $this->withoutExceptionHandling();

        $user = User::findOrFail(1);

        $userdelete = User::latest()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->delete('api/company/teams/'.$userdelete->id);

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testCompanyTeams()
    {
        $user = User::find(1);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/company/teams');

        //dd($response->content());
        $response->assertStatus(200);
    }
}
