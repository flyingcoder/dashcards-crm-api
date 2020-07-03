<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConfigurationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the rob can view the ben.
     *
     * @param  \App\User  $rob
     * @param  \App\User  $ben
     * @return mixed
     */
    public function update()
    {
        if( !in_array(auth()->user()->email, config('telescope.allowed_emails')) ) {
            abort(403, 'Not enought permission!');
        }
    }
}