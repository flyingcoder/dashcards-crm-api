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
    	
        return auth()->user()->company()->paginatedCompanyMembers(request());
    }

    public function member($id)
    {
    	$user = User::findOrFail($id);
        $user->location  = $user->getMeta('location', 'Somewhere');
        $user->total_hours = '244'; //todo calculation
        $user->rate_per_hour = '$55'; //todo calculation

    	$user->load('teams');

        $user->getAllMeta();

        $user['week_hours'] = $user->totalTimeThisWeek();

        $user->tasks;

        return $user;
    }
}
