<?php

namespace App\Events;


use App\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GroupChatSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $sender;
    public $receivers;

    public function __construct(Message $message)
    {
        $this->message = $message;
        $this->sender = $message->sender;
        $this->receivers = $message->conversation->users;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $channels = [];
        foreach ($this->receivers as $receiver) {
            $channels[] = new Channel('chat-as-users.' . $receiver->id);
        }

        return $channels;
    }
}
