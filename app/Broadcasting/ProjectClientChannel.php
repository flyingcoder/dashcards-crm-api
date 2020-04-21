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
     * @return void
     */
    public function __construct(MembersRepository $repo)
    {
        $this->repo = $repo;
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
        
        $project_company = $project->company;
        $user_company = $user->company();

        if ($user->hasRole('admin') && (int) $user_company->id === (int) $project_company->id) {
            return true;
        }

        $conversation = $project->conversations()->where('type', 'client')->first();

        if (!$conversation) {
            $data = array(
                'type' => 'client',
                'group_name' => $user_company->name." Client Message Group",
                'company_id' => $user_company->id,
            );
            $admins = $this->repo->getCompanyAdmins($user_company)->pluck('id')->toArray() ?? [];
            $managers = $this->repo->getCompanyManagers($user_company)->pluck('id')->toArray() ?? [];
            $participants = array_unique(array_merge($admins, $managers));

            $conversation = Chat::createConversation($participants, $data);
            $conversation->type = 'client';
            $conversation->project_id = $projectId;
            $conversation->save();
        }

        if ($conversation->users()->where('user_id', '=', $user->id)->first()) {
            return true;
        }
        return false;
    }
}
