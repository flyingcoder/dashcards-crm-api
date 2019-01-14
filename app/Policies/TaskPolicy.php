<?php

namespace App\Policies;

use App\User;
use App\Task;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the rob can view the ben.
     *
     * @param  \App\User  $rob
     * @param  \App\User  $ben
     * @return mixed
     */
    public function index()
    {
        if( !auth()->user()->hasRole('admin|manager|default-admin-'.auth()->user()->company()->id) && auth()->user()->can('view.all-task') )
            abort(403, 'Not enought permission!');
    }

    /**
     * Determine whether the rob can view the ben.
     *
     * @param  \App\User  $rob
     * @param  \App\User  $ben
     * @return mixed
     */
    public function view(Task $task)
    {
        if($task->company() != auth()->user()->company())
            abort(403, 'Task not found!');
    }

    /**
     * Determine whether the ben can create users.
     *
     * @return mixed
     */
    public function create()
    {
       if( !auth()->user()->hasRole('admin|manager|default-admin-'.auth()->user()->company()->id) && !auth()->user()->can('create.task') )
          abort(403, 'Not enought permission to create a task!');
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
        if(!auth()->user()->hasRole('admin|default-admin-'.auth()->user()->company()->id) && !auth()->user()->can('update.task') )
          abort(403, 'Not enought permission!');
    }

    /**
     * Determine whether the rob can delete the ben.
     *
     * @param  \App\User  $rob
     * @param  \App\User  $ben
     * @return mixed
     */
    public function delete(Task $task)
    {
        if( !auth()->user()->hasRole('admin|default-admin-'.auth()->user()->company()->id) && !auth()->user()->can('delete.task') )
            abort(403, 'Not enought permission!');

        if( $task->company() != auth()->user()->company() )
            abort(403, 'Task not found!');
    }
}
