<?php

namespace Tests\Feature;

use Gate;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserPolicyTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUserPolicyView()
    {
    	$current = User::find(2);
    	$user = User::find(1);
    	//dd($user->hasRole('ghost'));
    	//dd($current->can('view', $user));
        $this->assertTrue(true);
    }
}
