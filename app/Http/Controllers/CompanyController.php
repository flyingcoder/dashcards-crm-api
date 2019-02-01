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
    	if(request()->has('all') && request()->all)
            return auth()->user()->company()->allTeamMembers();
    	
        return auth()->user()->company()->paginatedCompanyMembers(request());
    }

    public function member($id)
    {
    	$user = User::findOrFail($id);

    	return $user->load('teams');
    }
}
