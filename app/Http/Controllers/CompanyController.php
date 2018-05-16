<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function members()
    {
    	return auth()->user()->company()->allCompanyMembers();
    }

    public function teams()
    {
    	return auth()->user()->company()->allTeamMembers();
    }
}
