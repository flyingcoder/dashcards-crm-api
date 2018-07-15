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

        if(request()->has('paginated'))
            return $company->selectTemplate(request());

        return $company->paginatedTemplates(request());
    }

    public function milestonesTask($id)
    {
        if(!is_null(Template::find($id)))
            return view('pages.milestone-task-templates')->with(['id'=>$id]);
        else
            return redirect('milestones');
    }

    public function milestones($id)
    {
        if(!request()->ajax())
           return view('pages.milestone-templates', ['template_id' => $id]);

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
        $template = Template::findOrFail($id);

        return $template->milestones()
                        ->latest()
                        ->paginate();
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
        //request()->validate($this->rules);

        $template = Template::findOrFail($id);

        $milestone = Milestone::create([
            'project_id' => 0,
            'title' => request()['milestone']['title'],
            'days' => request()['milestone']['days'],
            'status' => request()['milestone']['status']
        ]);

        foreach (request()['tasks'] as $key => $task) {
             $milestone->tasks()->create([
                'title' => $task['title'],
                'description' => $task['description'],
                'days' => $task['days'],
                'status' => 'open'
             ]);
         }

        return $template->milestones()->attach($milestone);
    }
}
