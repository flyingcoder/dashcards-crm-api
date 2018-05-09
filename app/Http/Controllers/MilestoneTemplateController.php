<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\MilestoneTemplateRequest;
use App\MilestoneTemplate;
use App\Project;
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

    public function replicate($id, Request $request){
        $milestone = new Milestone();
        $milestone->replicate($request, Project::findOrfail($id));
    }
}
