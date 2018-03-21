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
        $user = User::find(1);
        
        $data = [
            'subject_type' => 'App\Company'
        ];
        /*
        $response = $this->actingAs($user, 'api')
                         ->post('api/timer/pause', $data);

        //dd($response->content());
        $response->assertStatus(200);*/
    }

    public function testTaskStartTime()
    {
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
