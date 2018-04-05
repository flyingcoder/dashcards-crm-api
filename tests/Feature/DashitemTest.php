<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashitemTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAddDashitem()
    {
        $user = User::find(1);

        $data = [
            'dashitem_id' => [1,2,3,4]
        ];

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->post('api/dashboard/default/dashitems', $data);

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testGetDashitems()
    {
        $user = User::find(1);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/dashitems');

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

        $data = [
            'item_sequence' => [
                [
                    'slug' => 'calendar',
                    'order' => 1
                ],
                [
                    'slug' => 'tasks',
                    'order' => 2
                ],
                [
                    'slug' => 'timeline',
                    'order' => 3
                ],
                [
                    'slug' => 'passbox',
                    'order' => 4
                ],
                [
                    'slug' => 'timer',
                    'order' => 5
                ],
                [
                    'slug' => 'clients',
                    'order' => 6
                ],
                [
                    'slug' => 'payment',
                    'order' => 7
                ],
                [
                    'slug' => 'invoice',
                    'order' => 8
                ]
            ]
        ];

    	$response = $this->actingAs($user, 'api')
    					 ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
    					 ->put('api/dashitems/1/order', $data);

    	//dd($response->content());
    	$response->assertStatus(200);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testDashboardVisibility()
    {
        $user = User::find(1);

        $data = [
            'item_sequence' => [
                [
                    'slug' => 'calendar',
                    'visible' => 0
                ],
                [
                    'slug' => 'tasks',
                    'visible' => 1
                ],
                [
                    'slug' => 'timeline',
                    'visible' => 1
                ],
                [
                    'slug' => 'passbox',
                    'visible' => 1
                ],
                [
                    'slug' => 'timer',
                    'visible' => 1
                ],
                [
                    'slug' => 'clients',
                    'visible' => 1
                ],
                [
                    'slug' => 'payment',
                    'visible' => 1
                ],
                [
                    'slug' => 'invoice',
                    'visible' => 0
                ]
            ]
        ];

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->put('api/dashitems/1/visibility', $data);

        dd($response->content());
        $response->assertStatus(200);
    }
}
