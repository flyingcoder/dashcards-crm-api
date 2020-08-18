<?php

namespace App\Events;

use App\Message;
use App\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PrivateChatSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Message
     */
    public $message;

    /**
     * @var User
     */
    public $sender;

    /**
     * @var User
     */
    public $receiver;

    /**
     * Create a new event instance.
     *
     * @param Message $message
     * @param User $receiver
     */
    public function __construct(Message $message, User $receiver)
    {
        $this->message = $message;
        $this->sender = $message->sender;
        $this->receiver = $receiver;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('chat-as-user.' . $this->receiver->id);
    }
}
