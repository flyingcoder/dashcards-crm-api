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

        $user = User::latest()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/timer/stop');

        //dd($response->content());
        $response->assertStatus(201);
    }

    public function testTaskStartTime()
    {
        $this->withoutExceptionHandling();

        $user = User::find(1);
        
        $data = [
            'subject_type' => 'App\Task',
            'subject_id' => 1,
            'description' => 'Task Timer'
        ];
        
        $response = $this->actingAs($user, 'api')
                         ->post('api/timer/back', $data);

        //dd($response->content());
        $response->assertStatus(200);
    }
}
