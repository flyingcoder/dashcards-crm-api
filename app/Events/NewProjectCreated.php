<?php

namespace App\Events;

use App\Project;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class NewProjectCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $project;
    public $template_name;

    /**
     * Create a new event instance.
     *
     * @param Project $project
     * @param string $template_name
     */
    public function __construct(Project $project, $template_name = 'admin_template:new_project_created')
    {
        $this->project = $project;
        $this->template_name = $template_name;
    }

}
