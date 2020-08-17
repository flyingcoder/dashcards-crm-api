<?php

namespace App\Events;

use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * Class ActivityEvent
 * @package App\Events
 */
class ActivityEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var
     */
    public $cause_by;

    /**
     * @var
     */
    public $description;

    /**
     * Create a new event instance.
     *
     * @param $activity
     * @param $description
     */
    public function __construct($activity, $description)
    {
         $this->cause_by = User::find($activity->causer_id);

         $this->description = $description;
    }

    /**
     * Determine if this event should broadcast.
     *
     * @return bool
     */
    public function broadcastWhen()
    {
        return config('activitylog.broadcast_activity_event', false);
    }
    
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        if(Auth::check())
            return new PrivateChannel('activity.log.'.auth()->user()->id);

        return new PrivateChannel('activity.log');
    }
}
