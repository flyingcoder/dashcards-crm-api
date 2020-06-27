<?php

namespace App\Policies;

use App\User;
use App\Form;
use Illuminate\Auth\Access\HandlesAuthorization;

class FormPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the ben can create forms.
     *
     * @return mixed
     */
    public function create()
    {
       if( !auth()->user()->hasRoleLikeIn(['admin','manager','default-admin-'.auth()->user()->company()->id]) && !auth()->user()->can('create.forms') )
          abort(403, 'Not enought permission to create a form!');
    }

    /**
     * Determine whether the ben can update forms.
     *
     * @return mixed
     */
    public function update(Form $form)
    {
       if( !auth()->user()->hasRoleLikeIn(['admin','manager','default-admin-'.auth()->user()->company()->id]) && !auth()->user()->can('update.forms') )
          abort(403, 'Not enought permission to update a form!');
    }

    /**
     * Determine whether the ben can delete forms.
     *
     * @return mixed
     */
    public function delete(Form $form)
    {
       if( !auth()->user()->hasRoleLikeIn(['admin','manager','default-admin-'.auth()->user()->company()->id]) && !auth()->user()->can('delete.forms') )
          abort(403, 'Not enought permission to delete a form!');
    }
}
