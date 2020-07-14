<?php

namespace App\Events;

use App\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewTaskCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $task;
    public $template_name;

    /**
     * Create a new event instance.
     *
     * @param Task $task
     * @param string $template_name
     */
    public function __construct(Task $task, $template_name = 'admin_template:new_task')
    {
        $this->template_name = $template_name;
        $this->task = $task;
    }

}
