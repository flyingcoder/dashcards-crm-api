<?php

namespace App\Events;

use App\User;
use App\MessageNotification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ChatNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $notification = MessageNotification::where([
                            ['message_id', '=', $message->id],
                            ['is_sender', '=', 0],
                            ['is_seen', '=', 0]
                        ])->first();

        $data = collect($notification);

        $data->put('body', $message->body);

        $sender = $message->sender()
                          ->select('id', 'first_name', 'last_name', 'image_url')
                          ->first();

        $data->put('sender', $sender);

        $this->notification = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('chat.notification.'.$this->notification['sender']->id);
    }
}
