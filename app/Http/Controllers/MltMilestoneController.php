<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MltMilestone;

class MltMilestoneController extends Controller
{
    public function index(){
        if(!request()->ajax())
           return view('pages.mlt-milestones');
           
        try {
            $milestone = new MltMilestone;
            return $milestone->orderBy('created_at','desc')->paginate(10);
        } 
        catch (\Exception $ex) {
            return response(['message' => $ex->getMessage()], 500);
        }
    }

}
