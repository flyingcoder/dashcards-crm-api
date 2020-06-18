<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the rob can view the ben.
     *
     * @param  \App\User  $rob
     * @param  \App\User  $ben
     * @return mixed
     */
    public function view(User $ben)
    {
        if($ben->company() != auth()->user()->company())
            abort(403, 'User not found!');
    }

    /**
     * Determine whether the ben can create users.
     *
     * @return mixed
     */
    public function create()
    {
       if(!auth()->user()->hasRoleLikeIn(['admin','manager','default-admin-'.auth()->user()->company()->id]))
          abort(403, 'Not enought permission!');
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
        if(!auth()->hasRoleLikeIn(['admin','manager','default-admin-'.auth()->user()->company()->id]))
          abort(403, 'Not enought permission!');
    }

    /**
     * Determine whether the rob can delete the ben.
     *
     * @param  \App\User  $rob
     * @param  \App\User  $ben
     * @return mixed
     */
    public function delete(User $ben)
    {
        if(!auth()->user()->hasRoleLikeIn(['admin','default-admin-'.auth()->user()->company()->id]))
            abort(403, 'Not enought permission!');

        if($ben->company() != auth()->user()->company())
            abort(403, 'User not found!');
    }
}
