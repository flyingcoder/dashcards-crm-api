<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Task;
use App\Team;
use App\Service;
use App\Project;
use Illuminate\Http\Request;
use App\Policies\ProjectPolicy;
use Kodeine\Acl\Models\Eloquent\Role;
use Illuminate\Pagination\LengthAwarePaginator;

class ProjectController extends Controller
{
    protected $per_page = 5;
    protected $paginate = 5;

    public function index()
    {
        if(!request()->ajax())
            return view('pages.projects', ['projects' => [], 'personal' => false]);

        $company = Auth::user()->company();

        $result = $company->paginatedCompanyProjects(request());

        return $result;
    }

    public function save()
    {
        (new ProjectPolicy())->create();

        $clients =  Role::where('slug', 'client')->first()->users;
        $company = Auth::user()->company();

        return view('pages.projects-new', [
            'services' => collect($company->servicesList()),
            'clients' => $clients,
            'action' => 'add'
        ]);
    }

    public function update($id)
    {

        (new ProjectPolicy())->update($project);

        request()->validate([
            'client_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'start_at' => 'required|date',
            'end_at' => 'required|date',
            'location' => 'required',
            'description' => 'required'
        ]);

        $project = Project::findOrFail($id);

        $project->location = request()->location;
        $project->service_id = request()->service_id;
        $project->description = request()->description;
        $project->started_at = request()->start_at;
        $project->end_at = request()->end_at;

        if($project->client()->id != request()->client_id) {
            $project->members()->detach($project->client()->id);
            $project->members()->attach(request()->client_id, ['role' => 'client']);
        }

        $project->save();

        request()->session()->flash('message.level', 'success');
        request()->session()->flash('message.content', 'Project was successfully updated!');

        return back();
    }

    public function delete($id)
    {
        $project = Project::findOrFail($id);

        (new ProjectPolicy())->delete($project);

        return $project->destroy($id);
    }

    public function edit($id)
    {

        $project = Project::findOrFail($id);

        (new ProjectPolicy())->update($project);

        $company = Auth::user()->company();

        return view('pages.projects-new', [
            'services' => $company->servicesList(),
            'clients' => $company->allCompanyClients(),
            'action' => 'edit',
            'project' => $project
        ]);
    }

    public function store()
    {
        (new ProjectPolicy())->create();

        request()->validate([
            'client_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'start_at' => 'required|date',
            'end_at' => 'required|date',
            'location' => 'required',
            'description' => 'required'
        ]);

        $project = Project::create([
            'location' => request()->location,
            'service_id' => request()->service_id,
            'description' => request()->description,
            'started_at' => request()->start_at,
            'end_at' => request()->end_at,
            'status' => 'Active'
        ]);

        $project->members()->attach(request()->client_id, ['role' => 'client']);
        $project->members()->attach(Auth::user()->id, ['role' => 'manager']);

        request()->session()->flash('message.level', 'success');
        request()->session()->flash('message.content', 'Project was successfully added!');

        return back();
    }

    public function myProjects()
    {
        if(!request()->ajax())
            return view('pages.projects', ['personal' => true]);

        return Project::personal(request());

    }

    public function countProject()
    {
        return Project::personal(request())->count();
    }

    public function project($project_id)
    {
        $project = Project::findOrFail($project_id);

        (new ProjectPolicy())->view($project);

        return $project->load(['client', 'service']);
    }

    /*public function myProjectStatus($status)
    {
        $projects = Auth::user()
                        ->projects()
                        ->where('status', $status)
                        ->orderBy('created_at', 'desc')
                        ->paginate($this->per_page);

        if(Auth::user()->hasRole('client')) {
            $projects = Auth::user()
                            ->created_projects()
                            ->where('status', $status)
                            ->orderBy('created_at', 'desc')
                            ->paginate($this->per_page);
        }

        return view('pages.projects', ['projects' => $projects]);
    }*/

    public function status($status)
    {
        $projects = Project::where('status', $status)->orderBy('created_at', 'desc')->paginate($this->per_page);
        return view('pages.projects', ['projects' => $projects]);
    }
    //
    // public function getMembers($project_id)
    // {
    //     $project = Project::findOrFail($project_id);
		// return view('pages.project-hq.members', ['project_id' => $project_id, 'project' => $project]);
    // }

    public function getOverview($project_id)
    {
		return view('pages.project-hq.index', ['project_id' => $project_id]);
    }
    //
    // public function getFiles($project_id)
    // {
    //     $project = Project::findOrFail($project_id);
    //
    //     return view('pages.project-hq.files', ['project' => $project, 'project_id' => $project_id]);
    // }

    public function filesCount($project_id){
              $project = Project::findOrFail($project_id);			
              $images = $project->getMedia('project.files.images')->count();
              $videos = $project->getMedia('project.files.videos')->count();
              $documents = $project->getMedia('project.files.documents')->count();
			  $others = $project->getMedia('project.files.others')->count();
			  return response()->json([ 'images' => $images, 
								'videos' => $videos, 
								'documents' => $documents, 
								'others' => $others
						]);
		}

    public function tasks($project_id)
    {
        $project = Project::findOrFail($project_id);

        (new ProjectPolicy())->view($project);

        return $project->paginatedProjectTasks(request());
    }

    public function invoices($project_id){
        $project = Project::findOrFail($project_id);
        return $project->invoices()->paginate(10);
    }

    public function members($project_id){
        $project = Project::findOrFail($project_id);
        return $project->members()->paginate(10);
    }
    //for overview function kindly check the spreadsheet

    // public function getCalendar($project_id)
    // {
    //     return view('pages.project-hq.calendar', ['project_id' => $project_id]);
    // }
    //
    // public function getMessages($project_id)
    // {
    //     return view('pages.project-hq.messages', ['project_id' => $project_id]);
    // }
    //
    // public function getInvoices($project_id)
    // {
    //     return view('pages.project-hq.invoices', ['project_id' => $project_id]);
    // }
}
