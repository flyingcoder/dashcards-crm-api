<?php

namespace App\Events;

use App\Task;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskUpdated
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
    public function __construct(Task $task, $template_name = 'admin_template:task_update')
    {
        $this->template_name = $template_name;
        $this->task = $task;
    }
}
