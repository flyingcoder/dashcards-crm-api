<?php

namespace App\Http\Controllers;

use App\Milestone;
use App\Http\Requests\MilestoneRequest;
use App\Http\Requests\TaskRequest;

class MilestoneController extends Controller
{
    /**
     * @param $parent
     * @param $parent_id
     * @return mixed
     */
    public function index($parent, $parent_id)
    {
        $milestone = new Milestone();

        return $milestone->paginated($parent, $parent_id);
    }

    /**
     * @param $id
     * @param TaskRequest $request
     * @return mixed
     */
    public function addTasks($id, TaskRequest $request)
    {
        $milestone = Milestone::findOrFail($id);

        return $milestone->addTask();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function tasks($id)
    {
        $milestone = Milestone::findOrFail($id);

        return $milestone->getTasks();
    }

    /**
     * @param $parent
     * @param $parent_id
     * @param MilestoneRequest $request
     * @return mixed
     */
    public function store($parent, $parent_id, MilestoneRequest $request)
    {
        $milestone = new Milestone();

        return $milestone->store($parent, $parent_id);
    }

    /**
     * @param $parent
     * @param $parent_id
     * @param $milestone_id
     * @return mixed
     */
    public function milestone($parent, $parent_id, $milestone_id)
    {
        return Milestone::findOrfail($milestone_id)
            ->load(['tasks']);
    }

    /**
     * @param $parent
     * @param $parent_id
     * @param $milestone_id
     * @return mixed
     */
    public function update($parent, $parent_id, $milestone_id)
    {
        $milestone = Milestone::findOrFail($milestone_id);

        return $milestone->updateMilestone();
    }

    /**
     * @param $parent
     * @param $parent_id
     * @param $milestone_id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function delete($parent, $parent_id, $milestone_id)
    {
        $milestone = Milestone::findOrFail($milestone_id);

        if ($milestone->delete()) {
            return response('Milestone is successfully deleted.', 200);
        } else {
            return response('Failed to delete milestone.', 500);
        }
    }

    /**
     * milestone get title and id only for select input
     * @param $project_id
     * @return mixed
     */
    public function selectMilestone($project_id)
    {
        return Milestone::where('project_id', $project_id)->get(['id', 'title']);
    }
}
