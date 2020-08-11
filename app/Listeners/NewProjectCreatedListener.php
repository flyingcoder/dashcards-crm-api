<?php

namespace App\Listeners;

use App\Events\NewProjectCreated;
use App\Mail\DynamicEmail;
use App\Traits\TemplateTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class NewProjectCreatedListener implements ShouldQueue
{
    use TemplateTrait, InteractsWithQueue;

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
     * @param NewProjectCreated $event
     * @return void
     */
    public function handle(NewProjectCreated $event)
    {
        $project = $event->project;
        $team_emails = $project->team->pluck('email')->toArray();
        $subject = $event->type == 'project' ? 'New Project Created' : 'New Campaign Created';
        if (count($team_emails) > 0) {
            $template = $this->getTemplate($event->template_name, $project->company->id, true);
            if (!is_null($template)) {
                $content = $this->parseTemplate($event->template_name, $template->raw, $project);
                Mail::to($team_emails)->send(new DynamicEmail($content, $subject, null));
            }
        }
    }
}
