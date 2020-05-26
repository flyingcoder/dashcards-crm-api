<?php

namespace App\Broadcasting;

use App\User;

class CompanyChannel 
{
	/**
     * Authenticate the user's access to the channel.
     *
     * @param  \App\User  $user
     * @param  integer $projectId
     * @return array|bool
     */
    public function join(User $user, $companyId)
    {
    	if((int) $user->company()->id === (int) $companyId) {
			return $user;
		}
    }
}