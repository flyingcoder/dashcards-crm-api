<?php

namespace App\Http\Controllers;

use App\User;
use App\Service;
use Illuminate\Http\Request;

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
}
