<?php

namespace App\Events;

use App\Comment;
use App\Media;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMediaCommentCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;

    public $media;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Media $media, Comment $comment)
    {
        $this->comment = $comment;

        $this->media = $media;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('comment.media.'.$this->media->id);
    }
}
