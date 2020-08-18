<?php

namespace App\Broadcasting;

use App\Project;
use App\Repositories\MembersRepository;
use App\User;
use Chat;

class ProjectTeamChannel
{
    protected $repo;

    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct(MembersRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param \App\User $user
     * @param integer $projectId
     * @return array|bool
     */
    public function join(User $user, $projectId)
    {
        $project = Project::findOrFail($projectId);

        $project_company = $project->company;
        $user_company = $user->company();

        //user not belong to company
        if ((int)$user_company->id !== (int)$project_company->id) {
            return false;
        }

        $conversation = $project->teamProjectRoom();

        //user is part of the conversation
        if ($conversation->users()->where('id', $user->id)->exist())
            return true;

        //user has admin role
        if ($user->hasRoleLike('admin')) {
            $conversation->addParticipants([$user]);
            return true;
        }

        //user has client role
        if ($user->hasRoleLike('client')) {
            return false;
        }

        //user is part of the project
        if ($project->team()->where('id', $user->id)->exist()){
            $conversation->addParticipants([$user]);
            return true;
        }

        return false;
    }

}
