<?php

namespace App\Listeners;

use App\Events\NewActivity;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewActivityListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(NewActivity $event)
    {
        // $activity = $event->activity;

        // $user = $activity->causer();

        // $ids = $user->company()->membersID();

        // foreach ($ids as $key => $value) {
            
        // }
    }
}
