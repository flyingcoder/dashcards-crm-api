<?php

namespace App\Http\Controllers;

use App\Company;
use App\Milestone;
use App\Repositories\TemplateRepository;
use App\Template;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TemplateController extends Controller
{
    protected $repo;

    public function __construct(TemplateRepository $repo)
    {
        $this->repo = $repo;
    }

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

    public function invoices()
    {
        $company = auth()->user()->company();
        $templates = Template::where('replica_type', 'App\\Invoice')
            ->whereIn('company_id', [$company->id, 0]) //0-defaults
            ->orderBy('company_id', 'ASC')
            ->orderBy('id', 'ASC')
            ->paginate(20);
        $templateItems = $templates->getCollection();
        $data = collect([]);

        foreach ($templateItems as $key => $template) {
            $user = User::withTrashed()->where('id', $template->getMeta('creator'))->first();
            $data->push(array_merge($template->toArray(), ['creator' => $user, 'template' => $template->getMeta('template', null) ]));   
        }

        $templates->setCollection($data);
        return $templates;
    }

    public function getInvoiceFields()
    {
        $fields = $this->repo->getFields();
        return response()->json($fields, 200);
    }
    
    public function saveInvoiceTemplates()
    {
        request()->validate([
                'title' => 'string|min:5',
                'html' => 'string|min:50'
            ]);

        $company = auth()->user()->company();

        $template = $company->templates()->create([
            'name' => request()->title,
            'status' => 'active',
            'replica_type' => 'App\\Invoice',
            'created_at' => now()->format('Y-m-d H:i:s')
        ]);

        $template->setMeta('template', $this->repo->cleanHtml(request()->html));
        $template->setMeta('creator', auth()->user()->id);

        $template->template = $template->getMeta('template');
        $template->creator = request()->user();

        return $template;
    }

    public function updateInvoiceTemplates()
    {
        request()->validate([
                'id' => 'required|exists:templates,id',
                'title' => 'string|min:5',
                'html' => 'string|min:50'
            ]);

        $template = Template::findOrFail(request()->id);

        $template->name = request()->title;
        $template->updated_at = now()->format('Y-m-d H:i:s');
        $template->save();

        $template->setMeta('template', request()->html);

        $template->template = $template->getMeta('template');
        $template->creator = request()->user();

        return $template;
    }

    public function deleteInvoiceTemplates($id)
    {
        $template = Template::findOrFail($id);
        $template->delete();

        return $template;
    }
}
