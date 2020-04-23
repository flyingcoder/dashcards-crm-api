<?php

namespace App\Events;


use App\MessageNotification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GroupChatSent implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public $conversation;

    public function __construct($message, $conversation)
    {
        $this->message = $message;

        $this->conversation = $conversation;

        $this->createNotifications();
    }

    /**
     * Creates an entry in the message_notification table for each participant
     * This will be used to determine if a message is read or deleted.
     */
    public function createNotifications()
    {	
    	if ($this->conversation) {
    
	    	$notifications = [];

	        foreach ($this->conversation->users as $user) {
	            $is_sender = ($this->message['user_id'] == $user->getKey()) ? 1 : 0;

	            $notifications[] = [
	                'user_id'         => $user->getKey(),
	                'message_id'      => $this->message['id'],
	                'conversation_id' => $this->conversation->id,
	                'is_seen'         => $is_sender,
	                'is_sender'       => $is_sender,
	                'created_at'      => $this->message['created_at'],
	            ];
	        }
	        
	        MessageNotification::insert($notifications);
    	}
       
    }

    /**
	 * Get the data to broadcast.
	 *
	 * @return array
	 */
	public function broadcastWith()
	{
	    return $this->message;
	}

	/**
     * Get the channels the event should broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return new Channel('mc-chat-conversation.'.$this->conversation->id);
    }
}
