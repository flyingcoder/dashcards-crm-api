<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Company;
use App\Events\ProjectMessage;
use App\Http\Requests\ProjectRequest;
use App\Message;
use App\Milestone;
use App\Policies\ProjectPolicy;
use App\Project;
use App\Report;
use App\Repositories\ProjectRepository;
use App\Repositories\TimerRepository;
use App\Task;
use App\Team;
use App\Template;
use App\Traits\HasUrlTrait;
use App\User;
use Auth;
use Carbon\Carbon;
use Chat;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Kodeine\Acl\Models\Eloquent\Role;

class ProjectController extends Controller
{
    use HasUrlTrait;

    protected $paginate = 12;

    protected $timeRepo;
    protected $projRepo;
    protected $message_per_load = 10;

    public function __construct(TimerRepository $timeRepo, ProjectRepository $projRepo)
    {
        $this->timeRepo = $timeRepo;
        $this->projRepo = $projRepo;

        if (request()->has('per_page') && request()->per_page > 0) {
            $this->paginate = request()->per_page;
        }
    }

    public function index()
    {
        (new ProjectPolicy())->index();

        $company = Auth::user()->company();

        if(request()->has('all') && request()->all)
            return $this->projRepo->getCompanyProjectsList($company);
            // return $company->allCompanyProjects();

        return $this->projRepo->getCompanyProjects($company, request());
        // return $company->paginatedCompanyProjects(request());
    }

    public function sendMessages($id)
    {
        request()->validate([
            'from_id' => 'required|exists:users,id',
            'type' => 'required|in:'.implode(',', ['client', 'team'])
        ]);

        $body = request()->has('message') && !empty(request()->message) ? request()->message : ' ';
        $mention = createMentions($body);
        $body = $mention['content'];

        $from = User::findOrFail(request()->from_id);
        $project = Project::findOrFail($id);

        $conversation = $project->conversations()->where('type', request()->type)->firstOrFail();

        $message = Chat::message($body)
                       ->from($from)
                       ->to($conversation)
                       ->send();

        $message->body = getFormattedContent($message->body);
        $media = null;

        if (request()->has('file') && !is_null(request()->file) ) {
            $file = request()->file('file');
            $msg = Message::findOrFail($message->id);

            $media = $msg->addMedia($file)
                            ->preservingOriginal()
                            ->withCustomProperties([
                                'ext' => $file->extension(),
                                'user' => $from
                            ])
                            ->toMediaCollection('chat.file');

            $media = $msg->getFile();
        }

        $data = $message->toArray() + ['sender' => $from, 'media' => $media ];

        // GroupChatSent::dispatch($data, $conversation);
        // broadcast(new ProjectMessage($message, $model, request()->type))->toOthers();

        return response()->json($data, 201);
    }

    public function messages($id)
    {
        request()->validate([
            'type' => 'required|in:'.implode(',', ['client', 'team'])
        ]);

        $project = Project::findOrFail($id);

        if (auth()->check()) {
            $company = auth()->user()->company();
            $this->message_per_load = $company->others['messages_page_limits'] ?? 10;
        }

        $conversation = $project->conversations()->where('type', request()->type)->firstOrFail();

        $messages = $conversation->messages()->latest()->paginate($this->message_per_load);

        $items = $messages->getCollection();

        $data = collect([]);
        foreach ($items as $key => $msg) {
            $msg->body = getFormattedContent($msg->body);
            $message = Message::findOrFail($msg->id);
            $data->push(array_merge($msg->toArray(), ['media' =>  $message->getFile() ]));   
        }

        $messages->setCollection($data);

        return $messages;
    }

    public function reports($id)
    {
        $model = Project::findOrFail($id);

        return $model->projectReports();
    }

    public function newReport($id)
    {
        request()->validate([
            'title' => 'required',
            'url' => 'required'
        ]);

        $project = Project::findOrFail($id);

        return  $project->reports()->create([
            'company_id' => auth()->user()->company()->id,
            'title' => request()->title,
            'description' => request()->description,
            'url' => request()->url,
            'props' => $this->getPreviewArray(request()->url)
        ]);

    }

    public function updateReport($id, $report_id)
    {
        $project = Project::findOrFail($id);

        $report = $project->reports()->where('id', $report_id)->firstOrFail();

        if($report->updateReports()) {
            $report->fresh();
            return response()->json($report, 200);
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
            DB::beginTransaction();
            $project = Project::findOrFail($id);

            foreach (request()->members_id as $key => $user_id) {
                $user = User::findOrFail($user_id);
                if ($user->hasRole('admin|manager')) {
                    $project->members()->attach($user_id, ['role' => 'Manager']);
                } else {
                    $project->members()->attach($user_id, ['role' => 'Members']);
                }
            }
            DB::commit();
            return User::whereIn('id', request()->members_id)->with('tasks')->get();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], $e->getCode());
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
        request()->validate(['milestone_ids' => 'required|array']);

        try {
            DB::beginTransaction();
            $project = Project::findOrFail($id);

            $milestones = Milestone::whereIn('id', request()->milestone_ids)->get();

            foreach ($milestones as $key => $milestone) {
                $new_milestone = $milestone->replicate();
                $new_milestone->project_id = $project->id;
                $new_milestone->save();

                if($milestone->tasks->count() > 0) {
                    foreach ($milestone->tasks as $key => $task) {
                        $new_task = $new_milestone->tasks()->create([
                            'title' => $task->title,
                            'description' => $task->description,
                            'status' => $task->status,
                            'days' => $task->days,
                            'role_id' => $task->role_id ?? null
                        ]);
                    }
                }
            }
            DB::commit();
            return response()->json(['message' => 'Successfully copied milestone and its tasks'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], $e->getCode()); 
        }
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

    public function projectTaskTimers($id)
    {
        $project = Project::findOrFail($id);

        $tasks = $project->tasks()->select('tasks.*')
                    ->with('assigned')
                    ->orderBy('tasks.status', 'DESC')
                    ->orderBy('tasks.id', 'ASC')
                    ->paginate($this->paginate);

        $tasksItems = $tasks->getCollection();
        $data = collect([]);

        foreach ($tasksItems as $key => $task) {
            $timer = $this->timeRepo->getTimerForTask($task);
            $data->push(array_merge($task->toArray(), ['timer' => $timer , 'milestone' => $task->milestone ]));   
        }

        $tasks->setCollection($data);

        return $tasks;
    }

    public function save()
    {
        (new ProjectPolicy())->create();

        $clients =  Role::where('slug', 'client')->first()->users;

        $company = Auth::user()->company();

        return view('pages.projects-new', [
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
            'client_id' => 'required',
        ]);
        
        try {
            
            DB::beginTransaction();

            $project = Project::create([
                'type' => 'project',
                'title' => request()->title,
                'description' => request()->description,
                'started_at' => request()->start_at,
                'end_at' => request()->end_at ?? null,
                'status' => request()->project_status ?? 'Active',
                'company_id' => auth()->user()->company()->id,
                'props' => [
                        'business_name' => request()->business_name ?? null
                    ]
            ]);

            if(request()->has('extra_fields') && !empty(request()->extra_fields)){
                $project->setMeta('extra_fields', request()->extra_fields);
            }
            
            $project->members()->attach(request()->client_id, ['role' => 'Client']);

            if(request()->has('members')){
                foreach (request()->members as $value) {
                    $project->members()->attach($value, ['role' => 'Members']);
                }
            }
            
            if(request()->has('managers')){
                foreach (request()->managers as $value) {
                    $project->manager()->attach($value, ['role' => 'Manager']);
                }
            }

            DB::commit();

            //create return
            unset($project->members);

            $proj = Project::where('projects.id', $project->id)
                    ->with([ 
                        'projectManagers.user.meta',
                        'projectClient.user.meta',
                        'projectMembers.user.meta'
                    ])
                    ->first();

            $proj->extra_fields    = $project->getMeta('extra_fields');
            $proj->total_time      = $project->totalTime();
            $proj->progress        = $project->progress();
            $proj->tasks           = $project->tasks()->count();
            $proj->company_name    = $project->projectClient->user->meta['company_name']->value ?? "";
            $proj->client_id       = $project->projectClient->user->id ?? "";
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
        $project = Project::with(['manager', 'client'])->findOrFail($project_id);

        (new ProjectPolicy())->view($project);
        
        $client = $project->client[0];
        $project->total_time = $project->totalTime();
        $project->client_name = ucwords($client->fullname ?? '');
        $project->billed_to = ucwords($client->fullname ?? '');
        $project->manager_name = ucwords($project->manager[0]->fullname ?? '');
        $project->billed_from = ucwords($project->manager[0]->fullname ?? '');

        $project->tasks;
        $project->tasks->map(function ($item, $key) {
                $item['total_time'] = $item->total_time();
            });
        
        $project->location = $project->props['location'] ?? ($client->props['location'] ?? '');
        $company = Company::find($client->props['company_id'] ?? null);
        $project->company_name = $company ? $company->name : '';

        return $project;
    }

    public function projectInfo($id)
    {
        $project = Project::findOrFail($id);
        $project->client = $project->getClient();

        return $project;
    }

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

        $current_project_members = $project->members()
                             ->select('users.id')
                             ->pluck('id')
                             ->toArray();

        $company = auth()->user()->company();
        $all_company_members = $company->allCompanyMembers();

        $data = [];

        foreach ($all_company_members as $key => $user) {
            if ($user->hasRole('client')) {
                continue;
            }
            if (!in_array($user->id, $current_project_members)) {
                $data[] = $user;
            }
        }

        return $data;
    }

    public function membersAll($project_id){
        $project = Project::findOrFail($project_id);
        return $project->members()->get();
    }

    public function searchTasks($id)
    {
        $project = Project::findOrFail($id);
        $keyword = request()->keyword ?? '';

        $tasks = $project->tasks()
                ->where(function($query) use($keyword) {
                    $query->where('tasks.title', 'like', '%'.$keyword.'%')
                        ->orWhere('tasks.description', 'like', '%'.$keyword.'%');
                })
                ->select('tasks.*')
                ->paginate(10);

        return $tasks;
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
