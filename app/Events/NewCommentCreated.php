<?php

namespace App\Events;

use App\Task;
use App\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewCommentCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;

    public $task;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Task $task, Comment $comment)
    {
        $this->comment = $comment;

        $this->task = $task;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('comment.'.$this->task->id);
    }
}
