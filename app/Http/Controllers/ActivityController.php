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
        $per_page = request()->has('per_page') ? request()->per_page : 5;

        if (request()->has('page') && request()->page > 0) {
            $timelines = $company->timeline()
                    ->where('log_name', 'files')
                    ->latest()
                    ->paginate($per_page);

            return $timelines;
        }

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
        $per_page = request()->has('per_page') ? request()->per_page : 5;

        if (request()->has('page') && request()->page > 0) {
            return $project->activity()->where('log_name', 'files')
                    ->latest()
                    ->paginate($per_page);
        }
        
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
