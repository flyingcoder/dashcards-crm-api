<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CalendarTest extends TestCase
{   
    /*
    public function testIndex()
    {
        $user = User::find(1);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/calendars');

        //dd($response->content());
        $response->assertStatus(200);
    }  */

    
    public function testStore()
    {

        $user = User::find(1);

        $data = [
            'title' => 'Reports',
            'description' => 'Calendar events for reports',
            'properties' => json_encode([
                'label-color' => 'red'
            ])
        ];

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->post('api/calendars', $data);

        dd(substr($response->content(), 0, 500));

        $response->assertStatus(200);
    }

    public function testCalendar()
    {
        $user = User::find(1);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/calendars/1');

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testEvents()
    {
        $user = User::find(1);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/calendars/1/events');

        //dd($response->content());
        $response->assertStatus(200);
    }
}
