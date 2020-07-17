<?php

namespace App\Events;

use App\Project;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewProjectCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $project;
    public $template_name;
    public $type;

    /**
     * Create a new event instance.
     *
     * @param Project $project
     * @param string $type
     * @param string $template_name
     */
    public function __construct(Project $project, $type = 'project', $template_name = 'admin_template:new_project_created')
    {
        $this->project = $project;
        $this->template_name = $template_name;
        $this->type = $type;
    }

}
