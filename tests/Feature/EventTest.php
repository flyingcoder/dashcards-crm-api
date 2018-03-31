<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Events\NewTaskCommentCreated;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class EventTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testNewCommentEvent()
    {
        Event::fake();

        Event::assertDispatched(NewTaskCommentCreated::class, 1);
    }
}
