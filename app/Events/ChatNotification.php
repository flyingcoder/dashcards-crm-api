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
    public function __construct($message, User $user)
    {
        $notification = MessageNotification::where([
                            ['message_id', '=', $message['id']],
                            ['is_sender', '=', 0],
                            ['is_seen', '=', 0]
                        ])->first();

        $data = collect($notification);
        $data->put('body', $message['body']);
        $data->put('sender', $message['sender']);
        $data->put('to_id', $user->id);

        $this->notification = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('chat.notification.' . $this->notification['to_id']);
    }
}
