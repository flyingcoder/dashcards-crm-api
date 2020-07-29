<?php

namespace App\Listeners;

use App\Events\NewTaskCreated;
use App\Mail\DynamicEmail;
use App\Traits\TemplateTrait;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class NewTaskCreatedListener
{
    use TemplateTrait;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewTaskCreated  $event
     * @return void
     */
    public function handle(NewTaskCreated $event)
    {
        $task = $event->task;
        $project = $task->project;
        $assigned = $task->assigned->pluck('email')->toArray();
        if (count($assigned) > 0) {
            $template = $this->getTemplate($event->template_name, $project->company->id, true);
            if (!is_null($template)) {
                $content = $this->parseTemplate($event->template_name, $template->raw, $task);
                Mail::to($assigned)->send(new DynamicEmail($content, 'New Task Created', null));
            }
        }
    }
}
