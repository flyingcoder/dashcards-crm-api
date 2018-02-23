<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FormPolicy
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
     * Determine whether the ben can create users.
     *
     * @return mixed
     */
    public function create()
    {
       if( !auth()->user()->hasRole('admin|manager') && !auth()->user()->can('create.forms') )
          abort(403, 'Not enought permission to create a form!');
    }
}
