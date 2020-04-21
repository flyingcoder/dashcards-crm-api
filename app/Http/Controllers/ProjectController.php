<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Task;
use App\Team;
use App\Service;
use App\Project;
use App\Report;
use App\Comment;
use App\Template;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Policies\ProjectPolicy;
use Kodeine\Acl\Models\Eloquent\Role;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests\ProjectRequest;
use App\Events\ProjectMessage;
use DB;

class ProjectController extends Controller
{

    public function index()
    {
        (new ProjectPolicy())->index();

        $company = Auth::user()->company();

        if(request()->has('all') && request()->all)
            return $company->allCompanyProjects();

        return $company->paginatedCompanyProjects(request());
    }

    public function sendMessages($id)
    {
        $model = Project::findOrFail($id);

        $message = $model->sendMessages();

        broadcast(new ProjectMessage($message, $model, request()->type))->toOthers();

        return $message;
    }

    public function messages($id)
    {
        $model = Project::findOrFail($id);

        return $model->messages();
    }

    public function reports($id)
    {
        $model = Project::findOrFail($id);

        return $model->projectReports();
    }

    public function newReport($id)
    {
        $model = Project::findOrFail($id);

        return $model->createReports();
    }

    public function updateReport($id, $report_id)
    {
        $model = Report::findOrFail($id);

        if($model->updateReports()) {
            $model->fresh();
            return response()->json($model, 200);
        }

        return response()->json(['message' => 'error'], 500);
    }

    public function saveInvoice($project_id)
    {
        $model = Project::findOrFail($project_id);

        return $model->storeInvoice();
    }

    public function forInvoice($project_id)
    {
        $project = Project::findOrFail($project_id);

        $project->client_name = ucfirst($project->getClient()->last_name) .", ".ucfirst($project->getClient()->first_name);
        
        $project->billed_to = $project->getClient();

        $project->manager_name = ucfirst($project->getManager()->last_name) .", ".ucfirst($project->getManager()->first_name);

        $project->billed_from = $project->getManager();

        $project->service_name = $project->service()->get()->first()->name;

        $tasks = $project->taskWhereStatus('completed');

        $tasks->map(function ($item) {
            $item['total_time'] = $item->total_time();
        });

        unset($project->tasks);

        $project->tasks = $tasks;

        return $project;
    }

    public function invoice($id)
    {
        //(new ProjectPolicy())->index();
        $project = Project::findOrFail($id);
        
        return $project->paginatedInvoices();
    }

    public function assignMember($id)
    {
        request()->validate([
            'members_id' => 'required|array|min:1',
            'members_id.*'  => 'required|distinct|exists:users,id'
        ]);

        try {
             $project = Project::findOrFail($id);

            $project->members()->attach(request()->members_id);

            return User::whereIn('id', request()->members_id)->with('tasks')->get();
        } catch (Exception $e) {
            return response($e->getMessage(), $e->getCode());
        }
    }

    public function removeMember($id, $member_id)
    {

        $project = Project::findOrFail($id);

        $client  = $project->projectClient;
        $manager = $project->projectManager;

        if (in_array($member_id, [ $client->user_id, $manager->user_id ] )) {
            $user = User::findOrFail($member_id);
            $role = array_values($user->user_roles)[0] ?? 'user';
            abort(500, "Can't remove $role ".$user->fullname);
        } else {
            $project->members()->detach($member_id);
        }

        return $project->members;
    }

    public function bulkRemoveMember($id)
    {
        request()->validate([
            'ids' => 'required|array'
        ]);

        $project = Project::findOrFail($id);

        $remove_members = $project->projectMembers()->whereIn('user_id', request()->ids)->get();

        if (!$remove_members->isEmpty()) {
            foreach ($remove_members as $key => $member) {
                $project->members()->detach($member->user_id);
            }

            $message = $remove_members->count()." members successfully removed from project";
            return response()->json(['message' => $message,  'ids' => $remove_members ], 200);
        }

        $message = "Cannot remove member which are project manager or client of the project";
        return response()->json(['message' => $message ], 500);
    }


    public function milestoneImport($id)
    {
        $project = Project::findOrFail($id);

        return $project->importMilestones();
    }

    public function updateStatus($id)
    {
        $project = Project::findOrFail($id);

        (new ProjectPolicy())->update($project);

        $project->status = request()->status;

        $project->save();

        return response($project, 200);

    }

    public function timer($id)
    {
        $project = Project::findOrFail($id);

        (new ProjectPolicy())->view($project);

        return $project->totalTime();
    }

    public function myTimers($id)
    {
        $project = Project::findOrFail($id);

        return $project->timers();
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

    public function store(ProjectRequest $request)
    {
        (new ProjectPolicy())->create();

        request()->validate([
            'title' => 'required',
            'start_at' => 'required',
            'end_at' => 'required',
            'service_id' => 'required',
            'client_id' => 'required',
            'managers' => 'required|array'
        ]);
        
        try {
            
            DB::beginTransaction();

            $project = Project::create([
                'title' => request()->title,
                'service_id' => request()->service_id,
                'description' => request()->description,
                'started_at' => request()->start_at,
                'end_at' => request()->end_at,
                'status' => 'Active',
                'company_id' => auth()->user()->company()->id
            ]);

            if(request()->has('extra_fields') && !empty(request()->extra_fields)){
                $project->setMeta('extra_fields', request()->extra_fields);
            }
            
            $project->members()->attach(request()->client_id, ['role' => 'Client']);

            if(request()->has('managers')){
                foreach (request()->managers as $value) {
                    $project->manager()->attach($value, ['role' => 'Manager']);
                }
            }
            if(request()->has('members')){
                foreach (request()->members as $value) {
                    $project->members()->attach($value, ['role' => 'Members']);
                }
            }

            DB::commit();

            //create return
            unset($project->members);

            $proj = $project->with([ 
                'projectService',
                'projectManagers.user.meta',
                'projectClient.user.meta',
                'projectMembers.user.meta'
            ])->where('projects.id', $project->id)->first();

            $proj->extra_fields    = $project->getMeta('extra_fields');
            $proj->total_time      = $project->totalTime();
            $proj->progress        = $project->progress();
            $proj->tasks           = $project->tasks()->count();
            $proj->company_name    = $project->projectClient->user->meta['company_name']->value ?? "";
            $proj->client_id       = $project->projectClient->user->id ?? "";
            $proj->service_name    = $project->projectService->name ?? "";
            $proj->location        = $project->projectClient->user->meta['location']->value ?? "";

            return $proj;
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 522);
        }
    }

    public function update($id, ProjectRequest $request)
    {
        $project = Project::findOrFail($id);

        //(new ProjectPolicy())->update($project);

        $project->title = request()->title;
        $project->service_id = request()->service_id;
        $project->description = request()->description;
        $project->started_at = request()->start_at;
        $project->end_at = request()->end_at;

        if(request()->has('client_id')){
            if(count($project->client) == 0) {
                $project->members()->attach(request()->client_id, ['role' => 'Client']);
            } else if (isset($project->client()->first()->id) && $project->client()->first()->id != request()->client_id) {
                $project->members()->detach($project->client()->first()->id);
                $project->members()->attach(request()->client_id, ['role' => 'Client']);
            }
        }

        if(request()->has('managers')) {
            foreach (request()->managers as $value) {
                if(!$project->manager->contains($value))
                    $project->manager()->attach($value, ['role' => 'Manager']);
            }
        }
        if(request()->has('members')) {
            foreach (request()->members as $value) {
                if($value == request()->client_id)
                    abort(422, "Client can't be a member");

                if(!$project->members->contains($value))
                    $project->members()->attach($value, ['role' => 'Members']);
            }
        }

        $client = User::findOrFail(request()->client_id);

        $project->save();

        if(request()->has('extra_fields') && !empty(request()->extra_fields)){
            $project->setMeta('extra_fields', request()->extra_fields);
        }

        unset($project->members);

        $proj = $project->with([ 
                'projectService',
                'projectManagers.user.meta',
                'projectClient.user.meta',
                'projectMembers.user.meta'
            ])->where('projects.id', $project->id)->first();

        $proj->extra_fields    = $project->getMeta('extra_fields');
        $proj->total_time      = $project->totalTime();
        $proj->progress        = $project->progress();
        $proj->tasks           = $project->tasks()->count();
        $proj->company_name    = $project->projectClient->user->meta['company_name']->value ?? "";
        $proj->client_id       = $project->projectClient->user->id ?? "";
        $proj->service_name    = $project->projectService->name ?? "";
        $proj->location        = $project->projectClient->user->meta['location']->value ?? "";
        
        return $proj;
    }

    public function delete($id)
    {
        $project = Project::findOrFail($id);

        //(new ProjectPolicy())->delete($project);

        return $project->destroy($id);
    }

    public function bulkDelete()
    {
        request()->validate([
            'ids' => 'required|array'
        ]);

        $projects = Project::whereIn('id', request()->ids)->get();

        if (!$projects->isEmpty()) {
            foreach ($projects as $key => $project) {
                $project->delete();
            }
        }
        return response()->json(['message' => $projects->count().' project(s)  successfully deleted'], 200);
    }

    public function deleteReport($id, $report_id)
    {
        $report = Report::findOrFail($report_id);

        //(new ProjectPolicy())->delete($project);

        return $report->destroy($report_id);
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

    public function comments($id)
    {
        $project = Project::findOrFail($id);

        return $project->comments->load(['causer']);
    }

    public function addComments($id)
    {
        $project = Project::findOrFail($id);

        request()->validate([
            'body' => 'required'
        ]);

        $comment = new Comment([ 
            'body' => request()->body,
            'causer_id' => auth()->user()->id,
            'causer_type' => 'App\User'
        ]);

        $new_comment = $project->comments()->save($comment);

        //to be created
        //NewProjectCommentCreated::dispatch($project, $new_comment);

        return $new_comment;

    }

    public function countProject()
    {
        return Project::personal(request())->count();
    }

    public function project($project_id)
    {
        $project = Project::findOrFail($project_id);

        //(new ProjectPolicy())->view($project);
        
        $project->total_time = $project->totalTime();

        $project->client_name = ucfirst($project->getClient()->last_name) .", ".ucfirst($project->getClient()->first_name);
        
        $project->billed_to = ucfirst($project->getClient()->last_name) .", ".ucfirst($project->getClient()->first_name);

        $project->manager_name = ucfirst($project->getManager()->last_name) .", ".ucfirst($project->getManager()->first_name);

        $project->billed_from = ucfirst($project->getManager()->last_name) .", ".ucfirst($project->getManager()->first_name);

        $project->service_name = $project->service->name;

        $project->tasks;

        $project->tasks->map(function ($item, $key) {
            $item['total_time'] = $item->total_time();
        });

         $loc = $project->getClient()->getMeta('location');
            
        if(is_null($loc))
            $project->location = '';
        else
            $project->location = ucfirst($loc);

        $project->company_name = ucfirst($project->getClient()->getMeta('company_name'));

        return $project;
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


    public function getOverview($project_id)
    {
		return view('pages.project-hq.index', ['project_id' => $project_id]);
    }

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


    //will return all task of the project
    public function tasks($project_id)
    {
        $project = Project::findOrFail($project_id);

        //(new ProjectPolicy())->viewTask($project);

        //if user is admin return all task of a project
        return $project->paginatedProjectTasks();
    }

    //will return all task of the project
    public function myTasks($project_id)
    {
        $project = Project::findOrFail($project_id);

        //(new ProjectPolicy())->view($project);

        return $project->paginatedProjectMyTasks();
    }

    public function invoices($project_id){
        $project = Project::findOrFail($project_id);
        return $project->invoices()->paginate(10);
    }

    public function members($project_id){
        $project = Project::findOrFail($project_id);
        
        
        return $project->paginatedMembers();
    }

    public function newMembers($project_id)
    {
        $project = Project::findOrFail($project_id);

        $p_members = $project->members()
                             ->select('users.id')
                             ->get();

        $p_members->map(function ($user){
           unset($user->pivot);
        });

        $arr = $p_members->pluck('id');

        $company = auth()->user()->company();

        $c_user = $company->allCompanyMembers();

        $data = [];

        foreach ($c_user as $key => $user) {
            if(!in_array($user->id, $arr->all()))
                $data[] = $user;
        }

        return $data;
    }

    public function membersAll($project_id){
        $project = Project::findOrFail($project_id);
        return $project->members()->get();
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
