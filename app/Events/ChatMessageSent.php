<?php

namespace App\Events;

use App\Message;
use App\Traits\ConversableTrait;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Class ChatMessageSent
 * @package App\Events
 */
class ChatMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels, ConversableTrait;
    /**
     * @var Message
     */
    public $message;
    /**
     * @var mixed
     */
    protected $receivers;
    /**
     * @var mixed
     */
    protected $sender;

    /**
     * Create a new event instance.
     *
     * @param App\Message $message | Mozonza\Message $messsage
     */
    public function __construct($message)
    {
        $this->message = $message;
        $this->receivers = $message->conversation->users;
        $this->sender = $message->sender;

        $this->createNotifications($message);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $channel = [];
        foreach ($this->receivers as $receiver) {
            if ($receiver->id != $this->sender->id) {
                $channel[] = new PresenceChannel('as-user.'.$receiver->id);
            }
        }
        return $channel;
    }
}
