<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityTest extends TestCase
{
    public function testBasicTest()
    {
        $this->withoutExceptionHandling();

        $user = User::find(1);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/activities');

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testActivyLog()
    {
        $this->withoutExceptionHandling();

        $user = User::find(1);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/activities/log');

        dd($response->content());
        $response->assertStatus(200);
    }
}
