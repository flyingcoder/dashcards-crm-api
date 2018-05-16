<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationTest extends TestCase
{
    public function testUnread()
    {
    	$user = User::find(1);

    	$response = $this->actingAs($user, 'api')
    					 ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
    					 ->get('api/user/notifications');

    	dd($response->content());
    	$response->assertStatus(200);
    }

    public function testUnreadCount()
    {
    	$user = User::find(1);

    	$response = $this->actingAs($user, 'api')
    					 ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
    					 ->get('api/user/notifications/count');

    	//dd($response->content());
    	$response->assertStatus(200);
    }

    public function testMarkRead()
    {
    	$user = User::find(1);

    	$response = $this->actingAs($user, 'api')
    					 ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
    					 ->put('api/user/notifications/1', [ 'read' => true ]);

    	//dd($response->content());
    	$response->assertStatus(200);
    }
}
