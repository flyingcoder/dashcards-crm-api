<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Milestone;
use Auth;
use App\Project;
use App\Http\Requests\MilestoneRequest;



class MilestoneController extends Controller
{
    public function index($project_id)
    {
        if(!request()->ajax())
            return view('pages.project-hq.milestone', ['project_id' => $project_id]);   

        $project = Project::findOrFail($project_id);
        if(request()->has('all') && request()->all == true)
            return $project->milestones();

        return $project->milestones()->paginate(10);
    }

    public function addTasks($id)
    {
        $milestone = Milestone::findOrFail($id);

        $milestone->tasks()->create([
            'title' => request()->title,
            'days' => request()->days,
            'description' => request()->description,
            'status' => 'pending'
        ]);
    }

    public function tasks($id)
    {
        if(!request()->ajax())
            return view('pages.milestone-tasks')->with(['id' => $id]);

        $milestone = Milestone::findOrFail($id);

        if(!is_null($milestone))
            return $milestone->tasks()->paginate(10);

        return response(500);
    }

    public function milestone($id)
    {
        return Milestone::findOrfail($id)->load(['tasks']);
    }

    public function projectMilestone($project_id)
    {
        if(!request()->ajax())
            return view('pages.project-hq.milestone', ['project_id' => $project_id]);   

        $project = Project::findOrFail($project_id);

        return $project->milestones()->with(['tasks.assigned'])
        ->paginate(10);
    }

    public function save($project_id)
    {
    	// return view('pages.project-hq.tasks-new', ['project_id' => $project_id]);
    }

    public function store($project_id, MilestoneRequest $request)
    {
        try{
            $milestone = new Milestone();
            $milestone->store($request, Project::findOrfail($project_id));
            return response(['milestone' => $milestone], 200 );
            
        }
        catch (\Exception $ex) {
            return response(['message' => $ex->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        $milestone = Milestone::findOrFail($id);
        
        if($milestone->delete()){
            return response('Milestone is successfully deleted.', 200);
        }
        else {
            return response('Failed to delete milestone.', 500);
        }
    }

    // milestone get title and id only for select input
    public function selectMilestone($project_id){
        return Milestone::where('project_id', $project_id)->get(['id', 'title']);
    }

}
