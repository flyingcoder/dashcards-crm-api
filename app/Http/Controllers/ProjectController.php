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

        return $model->sendMessages();
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

        return $model->updateReports();
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
        
        $project->billed_to = ucfirst($project->getClient()->last_name) .", ".ucfirst($project->getClient()->first_name);

        $project->manager_name = ucfirst($project->getManager()->last_name) .", ".ucfirst($project->getManager()->first_name);

        $project->billed_from = ucfirst($project->getManager()->last_name) .", ".ucfirst($project->getManager()->first_name);

        $project->service_name = $project->service()->get()->first()->name;

        $tasks = $project->taskWhereStatus('completed');

        $tasks->map(function ($item) {
            $item['total_time'] = $item->total_time();
        });

        unset($project->tasks);

        $project->tasks = $tasks;

        return $project;
    }

    public function invoice($project_id)
    {
        //(new ProjectPolicy())->index();

        $project = Project::findOrFail($project_id);

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

        $project->members()->detach($member_id);

        return $project->members;
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
            'client_id' => 'required'
        ]);
        
        try{

            DB::beginTransaction();

            $project = Project::create([
                'title' => request()->title,
                //'location' => request()->location,
                'service_id' => request()->service_id,
                'description' => request()->description,
                'started_at' => request()->start_at,
                'end_at' => request()->end_at,
                'status' => 'Active',
                'company_id' => auth()->user()->company()->id
            ]);

            if(request()->has('comment') && request()->comment != ''){

                $comment = new Comment([ 
                    'body' => request()->comment,
                    'causer_id' => auth()->user()->id,
                    'causer_type' => 'App\User'
                ]);

                $new_comment = $project->comments()->save($comment);
            }

            
            $project->members()->attach(request()->client_id, ['role' => 'Client']);

            $project->members()->attach(Auth::user()->id, ['role' => 'Manager']);

            if(request()->has('members')){
                if(in_array(request()->client_id, request()->members)){
                    DB::rollback();
                    return response('Client cant be a member', 422);
                }
                elseif(in_array(Auth::user()->id, request()->members)){
                    DB::rollback();
                    return response('Manager cant be a member', 422);
                }
                foreach (request()->members as $value) {
                    $project->members()->attach($value, ['role' => 'Members']);
                }
            }

            $client = User::findOrFail(request()->client_id);

            DB::commit();

            //create return
            $project->members;

            $res = $project;

            $res->client_id = $client->id;

            $res->service_id = request()->service_id;

            $res->manager_id = auth()->user()->id;

            $res->manager_name = ucfirst(auth()->user()->last_name).", ".ucfirst(auth()->user()->first_name);

            $res->client_image_url = $client->image_url;

            $res->client_name = ucfirst($client->last_name).", ".ucfirst($client->first_name);

            $res->total_time = "00:00:00";

            $res->progress = 0;

            $res->service_name = ucfirst($project->service->name);

            return $res;

        } catch(\Exception $e){

            DB::rollback();      

            return response($e->getMessage(), 500);
            
        }
    }

    public function update($id, ProjectRequest $request)
    {
        $project = Project::findOrFail($id);

        (new ProjectPolicy())->update($project);

        dd($project);
        
        $project->title = request()->title;
        $project->service_id = request()->service_id;
        $project->description = request()->description;
        $project->started_at = request()->start_at;
        $project->end_at = request()->end_at;

        if(request()->has('client_id')){
            if(count($project->client) == 0) {
                $project->members()->sync(request()->client_id, ['role' => 'Client']);
            } else if (isset($project->client()->first()->id) && $project->client()->first()->id != request()->client_id) {
                $project->members()->detach($project->client()->first()->id);
                $project->members()->sync(request()->client_id, ['role' => 'Client']);
            }
        }

        if(request()->has('members')) {
            foreach (request()->members as $value) {
                $project->members()->sync(request()->members);
            }
        }

        $client = User::findOrFail(request()->client_id);

        $project->save();

        $project->members;

        //create return
        $res = $project;

        $res->client_id = $client->id;

        $res->service_id = request()->service_id;

        $res->manager_id = $project->getManager()->id;

        $res->manager_name = ucfirst($project->getManager()->last_name).", ".ucfirst($project->getManager()->first_name);

        $res->client_image_url = $client->image_url;

        $res->client_name = ucfirst($client->last_name).", ".ucfirst($client->first_name);

        $res->total_time = "00:00:00";

        $res->progress = 0;

        $res->service_name = ucfirst($project->service->name);

        return $res;
    }

    public function delete($id)
    {
        $project = Project::findOrFail($id);

        //(new ProjectPolicy())->delete($project);

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

        $p_members = $project->members()->get();

        $company = auth()->user()->company();

        $data = $company->members()->get();

        $data->filter(function ($user, $key) use ($p_members) {
            foreach ($p_members as $key => $pm) {
                return $user->id == $pm->id;
            }
        });
        
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
