<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageTest extends TestCase
{	
	
	public function testFetch()
    {
        $this->withoutExceptionHandling();

        $user = User::all()->first();

        $user2 = User::latest()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/chat/private/'.$user->id.'/'.$user2->id);

        dd($response->content());
        $response->assertStatus(200);
    }
    
    public function testSend()
    {
        $this->withoutExceptionHandling();

        $user = User::all()->first();

        $user2 = User::latest()->first();

        $data = [
        	'message' => 'Hello',
        	'from_id' => $user2->id,
        	'to_id' => $user->id
        ];

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->post('api/chat/private', $data);

        dd($response->content());
        $response->assertStatus(200);
    }
}
