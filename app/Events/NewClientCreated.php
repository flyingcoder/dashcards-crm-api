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

class NewClientCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $client;

    /**
     * Create a new event instance.
     *
     * @param User $client
     */
    public function __construct(User $client)
    {
        $this->client = $client;
    }
}
