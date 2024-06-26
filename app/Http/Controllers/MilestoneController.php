<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Milestone;
use Auth;
use App\Project;
use App\Http\Requests\MilestoneRequest;
use App\Http\Requests\TaskRequest;

class MilestoneController extends Controller
{
    public function index($parent, $parent_id)
    {
        $milestone = new Milestone();

        return $milestone->paginated($parent, $parent_id);
    }
    
    public function addTasks($id, TaskRequest $request)
    {
        $milestone = Milestone::findOrFail($id);

        return request()->id;
        return $milestone->addTask();
    }

    public function tasks($id)
    {
        $milestone = Milestone::findOrFail($id);

        return $milestone->getTasks();
    }

    public function store($parent, $parent_id, MilestoneRequest $request)
    {
        $milestone = new Milestone();

        return $milestone->store($parent, $parent_id);
    }

    public function milestone($parent, $parent_id, $milestone_id)
    {
        return Milestone::findOrfail($milestone_id)
                        ->load(['tasks']);
    }

    public function update($parent, $parent_id, $milestone_id)
    {
        $milestone = Milestone::findOrFail($milestone_id);

        return $milestone->updateMilestone();
    }

    public function delete($parent, $parent_id, $milestone_id)
    {
        $milestone = Milestone::findOrFail($milestone_id);
        
        if($milestone->delete()) {
            return response('Milestone is successfully deleted.', 200);
        } else {
            return response('Failed to delete milestone.', 500);
        }
    }

    // milestone get title and id only for select input
    public function selectMilestone($project_id) {

        return Milestone::where('project_id', $project_id)->get(['id', 'title']);

    }

}
