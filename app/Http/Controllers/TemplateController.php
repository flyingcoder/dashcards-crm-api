<?php

namespace App\Http\Controllers;

use App\Company;
use App\Milestone;
use App\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TemplateController extends Controller
{
    protected $rules = [
        'name' => 'required',
        'status' => 'required'
    ];

    public function index()
    {
        $company = auth()->user()->company();
        
        if(request()->has('all') && request()->all == true)
            return $company->selectTemplate();

        return $company->paginatedTemplates();
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

        $template->save();

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

    public function bulkDelete()
    {
        request()->validate([
            'ids' => 'required|array'
        ]);
        try {
            DB::beginTransaction();
            $templates = Template::whereIn('id', request()->ids)->get();

            if ($templates) {
                foreach ($templates as $key => $template) {
                    if (!$template->delete()) {
                        throw new \Exception("Failed to delete template {$template->title}!", 1);
                    }
                }
            }
            DB::commit();
            return response()->json(['message' => $templates->count().' template(s) was successfully deleted'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => "Some templates failed to delete"], 500);
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
