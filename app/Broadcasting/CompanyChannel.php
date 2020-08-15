<?php

namespace App\Broadcasting;

use App\User;

class CompanyChannel
{
    /**
     * Authenticate the user's access to the channel.
     * @param User $user
     * @param $company_id
     * @return array|bool
     */
    public function join(User $user, $company_id)
    {
        if ((int)$user->company()->id === (int)$company_id) {
            return $user->basics();
        }
        return false;
    }
}