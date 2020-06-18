<?php

namespace App\Events;

use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CompanyNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;

    public $company_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($company_id)
    {
        $this->company_id = $company_id;

        $data = collect([]);
        // $data->put('body', $message['body']);
        // $data->put('sender', $message['sender']);
        // $data->put('to_id', $user->id);

        $this->notification = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('friend-list-' . $this->company_id);
    }
}
