<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FormTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $user = User::find(1);
        /*
    	$response = $this->actingAs($user, 'api')
    					 ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
    					 ->get('api/forms');

    	//dd($response->content());
    	$response->assertStatus(200);*/
    }
}
