<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{

    public function testAddDashitem()
    {
        $user = User::all()->first();

        $data = [
            'dashitem_id' => [1,2,3]
        ];

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->post('api/dashboard/default/dashitems', $data);

        dd($response->content());
        $response->assertStatus(200);
    }

    public function testDeleteAllDashitem()
    {
        $user = User::find(1);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->delete('api/dashboard/default/dashitems/1');

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testDeleteDashitem()
    {
        $user = User::find(1);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->delete('api/dashboard/default/dashitems');

        //dd($response->content());
        $response->assertStatus(200);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testDashboard()
    {
        $user = User::find(1);

    	$response = $this->actingAs($user, 'api')
    					 ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
    					 ->get('api/dashboard/default/dashitems');

    	//dd($response->content());
    	$response->assertStatus(200);
    }
}
