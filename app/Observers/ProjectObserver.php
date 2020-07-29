<?php

namespace App\Observers;

use App\Project;
use App\Traits\TemplateTrait;
use Chat;

class ProjectObserver
{
    use TemplateTrait;
    /**
     * Handle the project "created" event.
     *
     * @param  \App\Project  $project
     * @return void
     */
    public function created(Project $project)
    {

        $participants = collect($project->members()->select('id')->get());
        $participants->flatten();

        $client_convo = Chat::createConversation($participants->all());
        $client_convo->project_id = $project->id;
        $client_convo->type = 'client';
        $client_convo->save();

        $team_convo = Chat::createConversation($participants->all());
        $team_convo->project_id = $project->id;
        $team_convo->type = 'team';
        $team_convo->save();

    }

    /**
     * Handle the project "updated" event.
     *
     * @param  \App\Project  $project
     * @return void
     */
    public function updated(Project $project)
    {
        //
    }

    /**
     * Handle the project "deleting" event.
     *
     * @param  \App\Project  $project
     * @return void
     */
    public function deleting(Project $project)
    {
        foreach(['milestones'] as $relation){
            foreach($project->{$relation} as $item)  {
                $item->delete();
            }
        }
    }
    
    /**
     * Handle the project "deleted" event.
     *
     * @param  \App\Project  $project
     * @return void
     */
    public function deleted(Project $project)
    {
        //
    }

    /**
     * Handle the project "restored" event.
     *
     * @param  \App\Project  $project
     * @return void
     */
    public function restored(Project $project)
    {
        //
    }

    /**
     * Handle the project "force deleted" event.
     *
     * @param  \App\Project  $project
     * @return void
     */
    public function forceDeleted(Project $project)
    {
        //
    }
}
