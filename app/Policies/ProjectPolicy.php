<?php

namespace App\Policies;

use App\User;
use App\Project;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        if( !auth()->user()->hasRole('admin|manager') && auth()->user()->can('view.all-project') )
            abort(403, 'Not enought permission!');
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
        if($project->company != auth()->user()->company())
            abort(403, 'Project not found!');
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
        if(
            $project->company != auth()->user()->company() &&
            !auth()->user()->hasRole('admin|manager') &&
            auth()->user()->can('view.project-task')
        ) {
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
       if( !auth()->user()->hasRole('admin|manager') && !auth()->user()->can('create.project') )
          abort(403, 'Not enought permission to create a project!');
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
        if(!auth()->user()->hasRole('admin') && !auth()->user()->can('update.project') )
          abort(403, 'Not enought permission!');
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
        if( !auth()->user()->hasRole('admin') && !auth()->user()->can('delete.project') )
            abort(403, 'Not enought permission!');

        if( $project->company != auth()->user()->company() )
            abort(403, 'Project not found!');
    }
}
