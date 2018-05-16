<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Events\NewActivity;
use Illuminate\Support\Facades\Event;
use Spatie\Activitylog\Models\Activity;
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

        $activity = Activity::first();

        //Event::assertDispatched(NewActivity::class,1);
        //event(new NewActivity($activity));
    }
}
