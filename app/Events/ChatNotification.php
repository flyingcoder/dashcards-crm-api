<?php

namespace App\Events;

use App\Traits\ConversableTrait;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Musonza\Chat\Models\Message;

class ChatNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels, ConversableTrait;

    public $message;
    public $sender;
    public $receivers;

    public function __construct(Message $message)
    {
        $this->message = $message;
        $this->sender = $message->sender;
        $this->receivers = $message->conversation->users;

        //$this->createNotifications($message);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('friend-list-' . $this->sender->company()->id);
    }
}
