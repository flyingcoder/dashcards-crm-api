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
class CompanyNotification extends Notification implements ShouldBroadcast
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
        /* for company events[
            'company' => INT,
            'targets' => [ARRAY OF USER IDS],
            'title' => STRING,
            'image_url' => URL, //company image, attachments etc
            'message' => STRING,
            'type' => STRING,
            'read_at' => null,
            'url' => URL
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
        $message = $notifiable->notifications()->latest()->first();
        if (!$message) {
            $message = $this->data;
        } else {
            $message = $message->toArray();
        }
        return new BroadcastMessage($message);
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
