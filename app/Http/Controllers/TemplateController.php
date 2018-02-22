<?php

namespace App\Http\Controllers;

use App\Template;
use App\Company;
use Illuminate\Http\Request;
use App\Milestone;

class TemplateController extends Controller
{
    protected $rules = [
        'name' => 'required',
        'status' => 'required',
        'type' => 'required'
    ];

    // Dustin Edit 02/06
    public function index()
    {
        if(!request()->ajax())
    	   return view('pages.milestone');

        $company = auth()->user()->company();

        return $company->paginatedTemplates(request());
    }

    public function milestones($id)
    {
        if(!request()->ajax())
           return view('pages.milestone-template', ['template_id' => $id]);

        $template = Template::findOrFail($id);

        return $template->paginatedMilestoneTemplate(request());
    }

    public function store()
    {
        request()->validate($this->rules);
        
        $template = Template::create([
            'company_id' => auth()->user()->company()->id,
            'name' => request()->name,
            'status' => request()->status,
            'replica_type' => request()->type
        ]);

        return $template;
    }

    // Dustin - 02/06
    public function milestone($id)
    {
        return Milestone::find($id);
    }
    
    public function projectDetails()
    {
    	return view('pages.project-details');
    }

    public function services()
    {
        return view('pages.services');
    }

    public function saveMilestone($id)
    {
        $template = Template::findOrFail($id);

        $milestone = Milestone::store(request());

        $template->milestone()->attach($milestone);
    }
}
