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
    	if(request()->has('paginate') && request()->paginate)
    		return auth()->user()->company()->paginatedCompanyMembers(request());
    	else
    		return auth()->user()->company()->allTeamMembers();
    }

    public function member($id)
    {
    	$user = User::findOrFail($id);

    	return $user->load('teams');
    }
}
