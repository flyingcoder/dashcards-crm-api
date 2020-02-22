<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Project;
use Illuminate\Http\Request;

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

    public function markRead($id)
    {
        $company = Activity::findOrFail($id);
    }

    public function unread()
    {
        $company = auth()->user()->company();
        
        return $company->activityLogUnRead();
    }
}
