<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function counts() {

    	$company = auth()->user()->company();

    	if(auth()->user()->hasRole('admin|manager') && auth()->user()->can('view.all-tasks')) {
    		$counts = [
	        	'projects' => $company->projects()->count(),
	        	'tasks' => $company->tasks()->where('status', 'open')->count(),
	        	'calendars' => 0, //to be added soon
	        	'timer' => $company->tasks()->where('status', 'open')->count(),
	        	'inbound' => 0, //this is about forms questionaires
	        	'outbound' => 0 //replied questionaires
	        ];
    	} else {
    		$counts = [
    			'projects' => auth()->user()->projects()->count(),
	        	'tasks' => auth()->user()->tasks()->where('status', 'open')->count(),
	        	'calendars' => 0, //to be added soon
	        	'timer' => auth()->user()->tasks()->where('status', 'open')->count(),
	        	'inbound' => 0, //this is about forms questionaires
	        	'outbound' => 0 //replied questionaires
    		];
    	}
    }
}
