<?php

namespace App\Policies;

use App\User;
use App\Service;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServicePolicy
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
        if( !auth()->user()->hasRoleLikeIn(['admin','manager','default-admin-'.auth()->user()->company()->id]) && auth()->user()->can('view.all-service') )
            abort(403, 'Not enought permission!');
    }

    /**
     * Determine whether the rob can view the ben.
     *
     * @param  \App\User  $rob
     * @param  \App\User  $ben
     * @return mixed
     */
    public function view(Service $service)
    {
        if($service->company() != auth()->user()->company())
            abort(403, 'Service not found!');
    }

    /**
     * Determine whether the ben can create users.
     *
     * @return mixed
     */
    public function create()
    {
       if( !auth()->user()->hasRoleLikeIn(['admin','manager']) && !auth()->user()->can('create.service') )
          abort(403, 'Not enought permission to create a service!');
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
        if(!auth()->user()->hasRole('admin') && !auth()->user()->can('update.service') )
          abort(403, 'Not enought permission!');
    }

    /**
     * Determine whether the rob can delete the ben.
     *
     * @param  \App\User  $rob
     * @param  \App\User  $ben
     * @return mixed
     */
    public function delete(Service $service)
    {
        if( !auth()->user()->hasRoleLikeIn(['admin','default-admin-'.auth()->user()->company()->id]) && !auth()->user()->can('delete.service') )
            abort(403, 'Not enought permission!');

        if( $service->company() != auth()->user()->company() )
            abort(403, 'Service not found!');
    }
}
