<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MltMilestone;
use App\MltTask;

class MltMilestoneController extends Controller
{
    public function index($id){
        if(!request()->ajax())
           return view('pages.mlt-milestones')->with('id',$id);
           
        try {
            $milestone = new MltMilestone;
            return $milestone->where('milestone_template_id', $id)->orderBy('created_at','desc')->paginate(10);
        } 
        catch (\Exception $ex) {
            return response(['message' => $ex->getMessage()], 500);
        }
    }

    public function store($id,Request $request){
        try {
            $milestone = $request['milestone'];
            $milestone['milestone_template_id'] = $id;
            $tasks = $request['tasks'];
            $milestone = MltMilestone::create($milestone);
            foreach($tasks as $t){
                $t['mlt_milestone_id'] = $milestone->id;
                MltTask::create($t);
            }
            return response(['milestone' => $milestone], 200 );
        } 
        catch (\Exception $ex) {
            return response(['message' => $ex->getMessage()], 500);
        }
    }

    public function all($id){
        $milestone = new MltMilestone;
        return $milestone->with('mltTasks')->where('milestone_template_id', $id)->orderBy('created_at','desc')->get();
    }

}
