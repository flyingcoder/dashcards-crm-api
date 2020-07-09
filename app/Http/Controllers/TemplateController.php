<?php

namespace App\Http\Controllers;

use App\Company;
use App\Milestone;
use App\Repositories\TemplateRepository;
use App\Template;
use App\Traits\TemplateTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TemplateController extends Controller
{
    use TemplateTrait;
    
    protected $repo;
    protected $rules = [
        'name' => 'required',
        'status' => 'required'
    ];

    public function __construct(TemplateRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index()
    {
        $company = auth()->user()->company();
        
        if(request()->has('all') && request()->all == true)
            return $company->selectTemplate();

        return $company->paginatedTemplates();
    }

    public function treeView()
    {
        $company = auth()->user()->company();
        
        return $this->repo->treeViewTemplates($company);
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

    public function saveEmailTemplate()
    {
        request()->validate([
            'name' => 'required|string',
            'value' => 'required|string|min:5',
        ]);
        
        $user = auth()->user();
        $company = $user->company();

        $key_name = $user->hasRoleLikeIn(['admin', 'manager']) ? 'admin_template:'.request()->name : 'client_template:'.request()->name;
   
        $template = $company->templates()
                    ->where('replica_type', 'App\\Template')
                    ->where('name', $key_name)
                    ->first();
        if (!$template) {
            $template = $company->templates()->create([
                    'replica_type' => 'App\\Template',
                    'name' => $key_name,
                    'status' => 'active',
                    'created_at' => now()->format('Y-m-d H:i:s')
                ]);
        }

        $template->setMeta('template', request()->value);
        $template->load('meta');

        return $template ;
    }

    public function saveGlobalEmailTemplate()
    {
        request()->validate([
            'name' => 'required|string',
            'value' => 'required|string|min:5'
        ]);
        
        $key_name = 'global_template:'.request()->name;
        
        $template = Template::where('replica_type', 'App\\Template')
                    ->where('company_id', 0)
                    ->where('name', $key_name)
                    ->first();
        if (!$template) {
            $template = Template::create([
                    'company_id' => 0,
                    'replica_type' => 'App\\Template',
                    'name' => $key_name,
                    'status' => 'active',
                    'created_at' => now()->format('Y-m-d H:i:s')
                ]);
        }

        $template->setMeta('template', request()->value);
        $template->load('meta');

        return $template ;
    }
    public function getEmailTemplates($type = null)
    {
        $user = auth()->user();
        $company = $user->company();
        $name = $user->hasRoleLikeIn(['admin', 'manager']) ? 'admin_template' : 'client_template';
        
        if (!is_null($type) && $type == 'global') {
            $name = 'global_template';
            return Template::with('meta')
                    ->where('replica_type', 'App\\Template')
                    ->where('name', 'like', $name.':%')
                    ->get();
        }

        $templates = $company->templates()
                    ->with('meta')
                    ->where('replica_type', 'App\\Template')
                    ->where('name', 'like', $name.':%')
                    ->get();

        return  $templates;
    }
}
