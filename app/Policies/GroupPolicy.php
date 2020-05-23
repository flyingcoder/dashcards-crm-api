<?php

namespace App\Policies;

use App\User;
use App\Company;
use Kodeine\Acl\Models\Eloquent\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
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
        if( !auth()->user()->hasRoleLikeIn(['admin','manager','default-admin-'.auth()->user()->company()->id]) && auth()->user()->can('group.view') )
            abort(403, 'Not enought permission!');
    }

    /**
     * Determine whether the rob can view the ben.
     *
     * @param  \App\User  $rob
     * @param  \App\User  $ben
     * @return mixed
     */
    public function view(Role $role)
    {
        if($role->company() != auth()->user()->company())
            abort(403, 'Group not found!');
    }

    /**
     * Determine whether the ben can create users.
     *
     * @return mixed
     */
    public function create()
    {
       if( !auth()->user()->hasRoleLikeIn(['admin','manager','default-admin-'.auth()->user()->company()->id]) && !auth()->user()->can('group.create') )
          abort(403, 'Not enought permission to create a group!');
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
        if(!auth()->user()->hasRoleLikeIn(['admin','default-admin-'.auth()->user()->company()->id]) && !auth()->user()->can('group.update') )
          abort(403, 'Not enought permission!');
    }

    /**
     * Determine whether the rob can delete the ben.
     *
     * @param  \App\User  $rob
     * @param  \App\User  $ben
     * @return mixed
     */
    public function delete(Role $role)
    {
        $roleCompany = Company::findOrFail($role->company_id);
        
        if( !auth()->user()->hasRoleLikeIn(['admin','default-admin-'.auth()->user()->company()->id]) && !auth()->user()->can('group.delete') )
            abort(403, 'Not enought permission!');

        if( $roleCompany != auth()->user()->company() )
            abort(403, 'Group not found!');
    }
}
