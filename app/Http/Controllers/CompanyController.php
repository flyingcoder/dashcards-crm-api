<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function members()
    {
    	return auth()->user()->company()->allCompanyMembers();
    }

    public function teams()
    {
        if(auth()->user()->hasRole('client'))
            return auth()->user()->clientStaffs();

    	if(request()->has('all') && request()->all)
            return auth()->user()->company()->allTeamMembers();
    	
        return auth()->user()->company()->paginatedCompanyMembers();
    }

    public function member($id)
    {
    	$user = User::findOrFail($id);

        $user->getAllMeta();

        $user['week_hours'] = $user->totalTimeThisWeek();
        
        $roles = $user->roles()->first();
        if(!is_null($roles))
            $user['group_name'] = $roles->id;

        $user->tasks;

        return $user;
    }

    public function invoices()
    {
        return auth()->user()->company()->invoices()->get();
    }
}
