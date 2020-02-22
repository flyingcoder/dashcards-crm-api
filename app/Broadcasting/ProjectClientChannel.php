<?php

namespace App\Broadcasting;

use App\Project;
use App\User;

class ProjectClientChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param  \App\User  $user
     * @param  integer $projectId
     * @return array|bool
     */
    public function join(User $user, $projectId)
    {
        $project = Project::findOrFail($projectId);

        if($project->members->contains($user->id)){
            return $user ? true : false;
        }
        return false;
    }
}
