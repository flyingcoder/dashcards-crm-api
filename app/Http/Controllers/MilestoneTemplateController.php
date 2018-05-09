<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\MilestoneTemplateRequest;
use App\MilestoneTemplate;
use App\Project;
use App\Milestone;
use App\Task;
use Auth;

class MilestoneTemplateController extends Controller
{

    public function index()
    {
        if(!request()->ajax())
           return view('pages.milestone-templates');

        try {
            $milestone = new MilestoneTemplate;
            return $milestone->with('user')->orderBy('created_at','desc')->paginate(10);
        } 
        catch (\Exception $ex) {
            return response(['message' => $ex->getMessage()], 500);
        }
    }

    public function store(MilestoneTemplateRequest $request){
        try {
            $newMilestone = $request->all();
            $newMilestone['user_id'] = Auth::user()->id;
            $milestone = MilestoneTemplate::create($newMilestone);
            return response(['milestone' => $milestone], 200 );
          } 
          catch (\Exception $ex) {
            return response(['message' => $ex->getMessage()], 500);
          }
    }

    public function update($id, MilestoneTemplateRequest $request){
        try {
            $milestone = MilestoneTemplate::find($id)->update($request->all());
            return response(['milestone' => $milestone], 200);
        } 
        catch (\Exception $ex) {
            return response(['message' => $ex->getMessage()], 500);
        }
    }

    public function destroy($id){
        try {
            return MilestoneTemplate::destroy($id);
        } 
        catch (\Exception $ex) {
            return response(['message' => $ex->getMessage()], 500);

        }
    }

    public function replicate($id,Request $request){
        $templates = $request->all();
        foreach($templates as $t){
            foreach($t as $m){
                $milestone = Milestone::create([
                    'project_id' => $id,
                    'title' => $m['title'],
                    'started_at' => date("Y-m-d",strtotime("now")),
                    'end_at' => date("Y-m-d",strtotime($m['days'] . ' days')),
                    'percentage' => $m['percentage'],
                    'status' => 'In Progress'
                ]);
                foreach($m['mlt_tasks'] as $task){
                    $new_task = Task::create([
                        'title' => $task['title'],
                        'description' => $task['description'],
                        'started_at' => date("Y-m-d",strtotime("now")),
                        'end_at' => date("Y-m-d",strtotime($task['days'] . ' days')),
                        'milestone_id' => $milestone->id,
                        'status' => 'Open'
                    ]);
                    if(!empty($task['assign'])){
                        $new_task->assigned()->attach($task['assign']);
                    }
                }
            } 
        }
    }

    public function all(){
        try {
            return MilestoneTemplate::select('id','title')->get();
        } 
        catch (\Exception $ex) {
            return response(['message' => $ex->getMessage()], 500);

        }
    }
}
