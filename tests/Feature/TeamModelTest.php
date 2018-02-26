<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Team;
use App\User;
use App\Company;

class TeamModelTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testServicesFunction()
    {
    	$user = User::findOrFail(1);

        $this->assertTrue(true);
    }
}
