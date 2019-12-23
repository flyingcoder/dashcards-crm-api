<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TimerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStartTime()
    {
        $this->withoutExceptionHandling();

        $user = User::all()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/timer/stop');

        dd($response->content());
        $response->assertStatus(201);
    }

    public function testGetTimer()
    {
        $this->withoutExceptionHandling();

        $user = User::all()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/timer');

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testTaskStartTime()
    {
        $this->withoutExceptionHandling();

        $user = User::find(1);
        
        $data = [
            'type' => 'task',
            'id' => 5
        ];
        
        $response = $this->actingAs($user, 'api')
                         ->post('api/timer/pause', $data);

        dd($response->content());
        $response->assertStatus(200);
    }

    public function testTimerTask()
    {
        $this->withoutExceptionHandling();

        $user = User::find(1);
        
        $data = [
            'type' => 'task',
            'id' => 5
        ];
        
        $response = $this->actingAs($user, 'api')
                         ->post('api/timer', $data);

        //dd($response->content());
        $response->assertStatus(200);
    }
}
