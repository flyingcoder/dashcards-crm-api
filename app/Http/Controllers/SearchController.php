<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function autocomplete($model)
    {
    	request()->validate([
    		'q' => 'required'
    	]);

    	$company = auth()->user()->company();

    	return $company->autocomplete($model);
    }
    /**
     *
     * !important all data should only be from the user company
     */
    public function globalSearch()
    {
    	request()->validate([
    		'keyword' => 'required'
    	]);

    	$data = collect([]);
    	$keyword = trim(request()->keyword);

    	$company = auth()->user()->company();

    	$usersMatchs =  $company->members()->where(function($query) use($keyword) {
    		$query->where('users.first_name', 'like', "%$keyword%")->orWhere('users.last_name', 'like', "%$keyword%")->orWhere('users.email', 'like', "%$keyword%");
    	})->select('users.*', DB::raw("'user' as modelType"))->limit(5)->get();

    	$projectMatchs = $company->companyProjects()->where(function($query) use($keyword) {
    		$query->where('projects.title', 'like', "%$keyword%")->orWhere('projects.description', 'like', "%$keyword%");
    	})->select('projects.*', DB::raw("'project' as modelType"))->limit(5)->get();

    	$data = $data->merge($usersMatchs)->merge($projectMatchs);

    	return $data->shuffle();
    }
}
