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
        'status' => 'required'
    ];

    public function index()
    {
        $company = auth()->user()->company();
        
        if(request()->has('all'))
            return $company->selectTemplate();

        return $company->paginatedTemplates();
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

        $type = 'App\Milestone'; 
        //default is milestone because this function as of now is for milestone only.

        if(request()->has('type'))
            $type = "App\\".ucfirst(request()->type);

        
        $template = Template::create([
            'company_id' => auth()->user()->company()->id,
            'name' => request()->name,
            'status' => request()->status,
            'replica_type' => $type
        ]);

        return $template;
    }

    public function update($id)
    {
        //request()->validate($this->rules);
        $template = Template::findOrFail($id);

        $type = 'App\Milestone'; 
        //default is milestone because this function as of now is for milestone only.

        if(request()->has('type'))
            $type = "App\\".ucfirst(request()->type);

        $template->name = request()->name;
        $template->status = request()->status;
        $template->replica_type = $type;

        return $template;
    }

    public function delete($id)
    {
        $model = Template::findOrFail($id);
        if($model->destroy($id)){
            return response('Template is successfully deleted.', 200);
        } else {
            return response('Failed to delete template.', 500);
        }
    }

    public function milestone($id)
    {
        $template = Template::findOrFail($id);

        return $template->milestones()
                        ->with('tasks')
                        ->latest()
                        ->paginate();
    }

    public function template($id)
    {
        return Template::findOrFail($id);
    }
}
