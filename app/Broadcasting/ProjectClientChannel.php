<?php

namespace App\Broadcasting;

use App\Project;
use App\Repositories\MembersRepository;
use App\User;
use Chat;

class ProjectClientChannel
{
    protected $repo;

    /**
     * Create a new channel instance.
     *
     * @param MembersRepository $repo
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

        if ((int)$user_company->id === (int)$project_company->id) {
            return true;
        }

        $conversation = $project->clientProjectRoom();

        //user is part of the conversation
        if ($conversation->users()->where('id', $user->id)->exist())
            return true;

        //user has admin role
        if ($user->hasRoleLike('admin')) {
            $conversation->addParticipants([$user]);
            return true;
        }

        $is_client_project = $project->client()->where('user_id', $user->id)->exists();
        $is_manager_project = $project->manager()->where('user_id', $user->id)->exists();

        if ($is_client_project || $is_manager_project) {
            $conversation->addParticipants([$user]);
            return true;
        }

        return false;
    }
}
