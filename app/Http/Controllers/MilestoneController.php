<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Milestone;
use Auth;
use App\Project;

class MilestoneController extends Controller
{
    public function index()
    {
       if(!request()->ajax())
            return view('pages.project-hq.milestone', ['project_id' => $project_id]);   

        $project = Project::findOrFail(2);

        return $project->milestones()->paginate(10);
    }

    public function projectMilestone($project_id)
    {
        if(!request()->ajax())
            return view('pages.project-hq.milestone', ['project_id' => $project_id]);   

        $project = Project::findOrFail($project_id);

        return $project->milestones()->with(['tasks'])
        ->paginate(10);
    }

    public function save($project_id)
    {
    	// return view('pages.project-hq.tasks-new', ['project_id' => $project_id]);
    }

    public function store($project_id)
    {
        $milestone = new Milestone();
        return $milestone->store(request(), Project::findOrfail($project_id));
    }

    public function delete($id)
    {
        $milestone = Milestone::findOrFail($id);
        return $milestone->destroy($id);
    }

    // milestone get title and id only for select input
    public function selectMilestone($project_id){
        return Milestone::where('project_id', $project_id)->get(['id', 'title']);
    }

}
