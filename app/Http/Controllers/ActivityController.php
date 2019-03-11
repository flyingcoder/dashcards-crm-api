<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;

class ActivityController extends Controller
{
    public function index()
    {
    	$company = auth()->user()->company();
        
    	return $company->allTimeline();
    }

    public function log()
    {
        $company = auth()->user()->company();
        
        return $company->activityLog();
    }

    public function project($project_id)
    {
    	$project = Project::findOrFail($project_id);

        return $project->activity;
    }
}
