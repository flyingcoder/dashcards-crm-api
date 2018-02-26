<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Timer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TimerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
    	$timer = new Timer();

    	dd($timer->start());

        $this->assertTrue(true);
    }
}
