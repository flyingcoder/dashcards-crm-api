<?php

namespace App\Policies;

use App\User;
use App\Project;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;


    protected function sameCompany(Project $project)
    {
        return ((int) $project->company_id == (int) auth()->user()->company()->id);
    }

    /**
     * Determine whether the rob can view the ben.
     *
     * @param  \App\User  $rob
     * @param  \App\User  $ben
     * @return mixed
     */
    public function index()
    {
        if( !auth()->user()->hasRoleLikeIn(['admin','manager','default-admin-'.auth()->user()->company()->id]) && auth()->user()->can('view.all-project') ) {
            abort(403, 'Not enought permission!');
        }
    }

    /**
     * Determine whether the rob can view the ben.
     *
     * @param  \App\User  $rob
     * @param  \App\User  $ben
     * @return mixed
     */
    public function view(Project $project)
    {
        if(!$this->sameCompany($project)){
            abort(403, 'Project not found!');
        }
    }

    /**
     * Determine whether the rob can view the ben.
     *
     * @param  \App\User  $rob
     * @param  \App\User  $ben
     * @return mixed
     */
    public function viewTask(Project $project)
    {   
        if(!$this->sameCompany($project)){
            abort(403, 'Project not found!');
        }

        if( !auth()->user()->hasRoleLikeIn(['admin','manager']) && auth()->user()->can('view.project-task') ) {
            abort(403, 'Project Tasks not found!');
        }
    }


    /**
     * Determine whether the ben can create users.
     *
     * @return mixed
     */
    public function create()
    {
        if( !auth()->user()->hasRoleLikeIn(['admin','manager','default-admin-'.auth()->user()->company()->id]) && !auth()->user()->can('create.project') ){
          abort(403, 'Not enought permission to create a project!');
        }
    }

    /**
     * Determine whether the rob can update the ben.
     *
     * @param  \App\User  $rob
     * @param  \App\User  $ben
     * @return mixed
     */
    public function update()
    {
        if(!$this->sameCompany($project)){
            abort(403, 'Project not found!');
        }

        if(!auth()->user()->hasRoleLikeIn(['admin','default-admin-'.auth()->user()->company()->id]) && !auth()->user()->can('update.project') ){
          abort(403, 'Not enought permission!');
        }
    }

    /**
     * Determine whether the rob can delete the ben.
     *
     * @param  \App\User  $rob
     * @param  \App\User  $ben
     * @return mixed
     */
    public function delete(Project $project)
    {
        if( !auth()->user()->hasRoleLikeIn(['admin','default-admin-'.auth()->user()->company()->id]) && !auth()->user()->can('delete.project') ){
            abort(403, 'Not enought permission!');
        }

        if(!$this->sameCompany($project)){
            abort(403, 'Project not found!');
        }
    }
}
