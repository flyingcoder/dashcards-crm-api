<?php

namespace App\Events;

use App\Project;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProjectMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $project;

    public $message;

    public $type;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message, Project $project, $type)
    {
        $this->project = $project;

        $this->message = $message;

        $this->type = $type;
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        if ($this->type == 'client') {
            return 'ProjectClientMessage';
        }
        return 'ProjectTeamMessage';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        if ($this->type == 'client') {
            return new PrivateChannel('project.client-message.'.$this->project->id);
        }
        return new PrivateChannel('project.team-message.'.$this->project->id);
    }
}
