<?php

namespace App\Notifications;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

/**
 * Class CompanyNotification
 * @package App\Notifications
 */
class ChatNotification extends Notification implements ShouldBroadcast
{
    use Queueable, InteractsWithSockets;

    /**
     * @var mixed
     */
    private $company_id;
    /**
     * @var array
     */
    public $data;

    /**
     * Create a new notification instance.
     *
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->data = $data;
        $this->company_id = $data['company'];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        /*for chat events[
            'company' => INT,
            'targets' => [ARRAY OF USER IDS],
            'title' => STRING,
            'message' => STRING,
            'type' => STRING,
            'read_at' => null,
            'url' => URL
             'sender' => [
                    'sender_id' => INT,
                    'fullname' => STRING,
                    'image_url' => URL
                ]
        ]*/
        return $this->data;
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param mixed $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->data);
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
