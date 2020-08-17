<?php

namespace App\Observers;

use App\Project;
use App\Traits\ConversableTrait;
use App\Traits\TemplateTrait;

class ProjectObserver
{
    use TemplateTrait, ConversableTrait;
    /**
     * Handle the project "created" event.
     *
     * @param  \App\Project  $project
     * @return void
     */
    public function created(Project $project)
    {
        $project->clientProjectRoom();
        $project->teamProjectRoom();
    }

    /**
     * Handle the project "updated" event.
     *
     * @param  \App\Project  $project
     * @return void
     */
    public function updated(Project $project)
    {
        $project->updateTeamProjectRoomUsers();
        $project->updateClientProjectRoomUsers();
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
