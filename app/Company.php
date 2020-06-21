<?php

namespace App;

use App\Activity;
use App\Permission;
use App\Project;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Kodeine\Acl\Models\Eloquent\Role;
use Laravel\Scout\Searchable;
use Plank\Metable\Metable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Company extends Model implements HasMedia
{
    use SoftDeletes,
        Metable,
        Searchable,
        HasMediaTrait;

    protected $table = 'companies';
    
    protected $paginate = 12;

    protected $fillable = ['name', 'email', 'domain', 'tag_line', 'short_description', 'long_description', 'company_logo', 'others', 'address', 'contact', 'is_private'];
    protected $casts = [
            'others' => 'array',
            'contact' => 'array'
        ];

    public function companyReports()
    {
        list($sortName, $sortValue) = parseSearchParam(request());

        $model = $this->reports();

        if(request()->has('sort') && !is_null($sortValue))
            $model->orderBy($sortName, $sortValue);
        else
            $model->orderBy('created_at', 'desc');

        if(request()->has('search') && !empty(request()->search)){
            $keyword = request()->search;

            $model->where(function ($query) use ($keyword) {
                        $query->where('title', 'like', '%' . $keyword . '%');
                        $query->orWhere('description', 'like', '%' . $keyword . '%');
                        $query->orWhere('create_at', 'like', '%' . $keyword . '%');
                      });
        }

        if(request()->has('per_page'))
            $this->paginate = request()->per_page;

        $data = $model->paginate($this->paginate);

        if(request()->has('all') && requet()->all)
            $data = $model->get();

        return $data;
    }

    public function timers()
    {
        return $this->hasMany(Timer::class);
    }

    public function allTimers()
    {
        $model = $this->timers();

        list($sortName, $sortValue) = parseSearchParam(request());

        if(request()->has('sort') && !is_null($sortValue))
            $model->orderBy($sortName, $sortValue);
        else
            $model->orderBy('created_at', 'desc');

        if(request()->has('search') && !empty(request()->search)){
            $keyword = request()->search;

            $model->where(function ($query) use ($keyword) {
                $query->where('timers.timer_name', 'like', '%' . $keyword . '%');
            });
        }

        if(request()->has('per_page'))
            $this->paginate = request()->per_page;

        $data = $model->paginate($this->paginate);

        return $data;

        if(request()->has('all') && requet()->all)
            $data = $model->get();

        return $data;
    }

    public function createReports()
    {   
        request()->validate([
            'title' => 'required',
            'url' => 'required'
        ]);

        return $this->reports()->create([
            'title' => request()->title,
            'description' => request()->description ?? null,
            'url' => request()->url
        ]);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function paginatedPermissions()
    {
        list($sortName, $sortValue) = parseSearchParam(request());

        if(request()->has('all') && request()->all)
            return Permission::where('company_id', $this->id)->get();

        $model = $this->permissions();

        if(request()->has('sort') && !is_null($sortValue))
            $model->orderBy($sortName, $sortValue);
        else
            $model->orderBy('created_at', 'desc');

        if(request()->has('search') && !empty(request()->search)){
            $keyword = request()->search;

            $model->where(function ($query) use ($keyword) {
                        $query->where('name', 'like', '%' . $keyword . '%');
                        $query->orWhere('description', 'like', '%' . $keyword . '%');
                        $query->orWhere('create_at', 'like', '%' . $keyword . '%');
                      });
        }

        if(request()->has('per_page'))
            $this->paginate = request()->per_page;

        return $model->paginate($this->paginate);
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function invoices()
    {
        return $this->members()
                    ->select('invoices.*')
                    ->where('invoices.deleted_at', null)
                    ->join('invoices', 'invoices.user_id', '=', 'users.id');
    }

    public function dashboards()
    {
        return $this->hasMany(Dashboard::class);
    }

    public function calendars()
    {
        return $this->hasMany(CalendarModel::class);
    }

    public function autocomplete($model)
    {
        $model = "search".ucfirst($model);

        return $this->{$model}(request()->q);
    }

    public function searchService($query)
    {
        $model = $this->services()
                    ->where(function($q) use ($query) {
                            $q->where('services.name', 'LIKE', "%{$query}%");
                    });
             

        return $model->get();
    }

    public function searchMember($query)
    {
        $model = $this->members()
                      ->select('users.*')
                      ->where('users.id', '!=', auth()->user()->id)
                      ->where(function($q) use ($query) {
                              $q->where('users.username', 'LIKE', "%{$query}%")
                                ->orWhere('users.first_name', 'LIKE', "%{$query}%")
                                ->orWhere('users.last_name', 'LIKE', "%{$query}%")
                                ->orWhere('users.email', 'LIKE', "%{$query}%");
                      });
             
        $clients = $this->clients()->get();

        foreach ($clients as $key => $client) {
            $model = $model->where('users.id', '!=', $client->id);
        }

        $projectMember = collect();

        if(request()->has('project_id') && !empty(request()->project_id)) {
            $project = Project::findOrFail(request()->project_id);
            $projectMember = $project->members()->select('users.id')->get();
        }

        foreach ($projectMember as $key => $member) {
            $model = $model->where('users.id', '!=', $member->id);
        }

        return $model->get();
    }

    public function searchClient($query)
    {
        $model = $this->clients()
             ->where(function($q) use ($query) {
                      $q->where('users.username', 'LIKE', "%{$query}%")
                        ->orWhere('users.first_name', 'LIKE', "%{$query}%")
                        ->orWhere('users.last_name', 'LIKE', "%{$query}%")
                        ->orWhere('users.email', 'LIKE', "%{$query}%");
            });

        return $model->get();
    }

    public function allPaginatedCalendar(Request $request)
    {
        list($sortName, $sortValue) = parseSearchParam($request);

        $calendars = $this->calendars();

        if($request->has('sort') && !empty(request()->sort))
            $calendars->orderBy($sortName, $sortValue);

        if(request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        return $calendars->paginate($this->paginate);
    }

    public function paginatedCompanyInvoices(Request $request)
    {
        list($sortName, $sortValue) = parseSearchParam($request);

        $invoices = $this->invoices();

        if($request->has('sort') && !empty(request()->sort))
            $invoices->orderBy($sortName, $sortValue);

        if(request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        $data = $invoices->paginate($this->paginate);

        if(request()->has('all') && request()->all)
            $data = $invoices->get();

        $data->map(function ($invoice) {
            $items = collect(json_decode($invoice->items, true));
            unset($invoice->items);
            $invoice->items = $items;
            $props = collect($invoice->props);
            unset($invoice->props);
            $invoice->props = $props;
            $invoice->billedTo = User::where('id', $invoice->billed_to)->first();
            $invoice->billedFrom = User::where('id', $invoice->billed_from)->first();
        });

        return $data;
    }

    public function templates()
    {
        return $this->hasMany(Template::class);
    }

    public function selectTemplate()
    {
        $type = "App\\" . ucfirst(request()->type);

        return $this->templates()
                    ->where('replica_type', $type)
                    ->get();
    }

    public function paginatedTemplates()
    {
        list($sortName, $sortValue) = parseSearchParam(request());

        $model = $this->templates();
        $table = 'templates';

        if(request()->has('type'))
            $model->where('replica_type', request()->type);

        if(request()->has('sort') && !empty(request()->sort))
            $model->orderBy($sortName, $sortValue);

        if(request()->has('search')){
            $keyword = request()->search;
            $model->where(function ($query) use ($keyword, $table) {
                        $query->where("{$table}.name", "like", "%{$keyword}%");
                        $query->where("{$table}.status", "like", "%{$keyword}%");
                  });
        }

        if(request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        return $model->paginate($this->paginate);
    }

    public function allTeamMembers()
    {
        $team = $this->teams()->where('slug', 'default-'.$this->id)->first();

        $data = $team->members()
                    ->select(
                        'users.id',
                        'users.job_title',
                        'users.email',
                        'users.first_name',
                        'users.last_name',
                        'users.image_url'
                    )
                    ->where('users.id', '!=', auth()->user()->id)
                    ->orderBy('users.created_at', 'DESC')
                    ->get();

        $data->map(function ($user) {
            $user['tasks'] = $user->tasks()->count();
            $user['projects'] = $user->projects()->count();
        });

        return $data;
    }
    
    public function members()
    {
        return User::join('team_user as tu', 'tu.user_id', '=', 'users.id')
                       ->join('teams', 'teams.id', '=', 'tu.team_id')
                       ->join('companies', function($join) {
                            $join->on('companies.id', '=', 'teams.company_id')
                                 ->where('companies.id', $this->id);
                       })->where('users.deleted_at', null);
    }
    public function membersWithTrashed()
    {
        return User::withTrashed()
                    ->join('team_user as tu', 'tu.user_id', '=', 'users.id')
                    ->join('teams', 'teams.id', '=', 'tu.team_id')
                    ->join('companies', function($join) {
                        $join->on('companies.id', '=', 'teams.company_id')
                                ->where('companies.id', $this->id);
                    });
    }

    public function allCompanyMembers()
    {
        $model = $this->members();

        if (request()->has('except') && !empty(request()->except) ) {
            $model = $model->whereNotIn('users.id', request()->except);
        }

        $model = $model->select(
                        'users.*',
                        DB::raw('CONCAT(CONCAT(UCASE(LEFT(users.last_name, 1)), SUBSTRING(users.last_name, 2)), ", ", CONCAT(UCASE(LEFT(users.first_name, 1)), SUBSTRING(users.first_name, 2))) AS name')
                      )->orderBy('users.created_at', 'DESC');

        if(request()->has('for') && request()->for == 'project')
            $model->where('users.id', '<>', auth()->user()->id);

        return $model->get();
                    
    }

    public function paginatedCompanyMembers()
    {
        list($sortName, $sortValue) = parseSearchParam(request());

        // $team = $this->teams()->where('slug', 'default-'.$this->id)->first();

        $members = $this->members()->select('users.*');

        if (request()->has('no-clients')) {
            $client_team = $this->clientTeam()->id ?? 0;
            $members = $members->where('teams.id', '<>', $client_team);
        }

        if(request()->has('sort') && !empty(request()->sort))
            $members->orderBy($sortName, $sortValue);
        else
            $members->orderBy('users.created_at', 'DESC');

        if(request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        $data = $members->paginate($this->paginate);

        $data->map(function ($user) {
            unset($user['projects']);
            $user->getAllMeta();
            $user['tasks'] = $user->tasks()->where('tasks.deleted_at', null)->count();
            $user['projects'] = $user->projects()->where('projects.deleted_at', null)->count();
            $roles = $user->roles()->first();
            if(!is_null($roles))
                $user['group_name'] = $roles->id;
        });

        return $data;
    }

    public function defaultTeam()
    {
        return $this->teams()->where('teams.slug', 'default-'.$this->id)->first();
    }

    public function clientTeam()
    {
        return $this->teams()->where('teams.slug', 'client-'.$this->id)->first();
    }

    public function clientStaffTeam()
    {
        return $this->teams()->where('teams.slug', 'client-staffs-'.$this->id)->first();
    }

    public function membersID()
    {
        $members = [];
        $teams = $this->teams;
        foreach ($teams as $team) {
            if(!empty($team->members)) {
                foreach ($team->members as $member) {
                    $members[] = $member->id;
                }
            }
        }

        return $members;
    }

    public function servicesList()
    {
        $members = $this->membersID();

        $services = Service::whereIn('user_id', $members)->get();

        return $services;
    }

    public function servicesNameList()
    {
        $members = $this->membersID();

        $services = Service::whereIn('user_id', $members)
                            ->pluck('name')
                            ->toArray();
        return collect($services)->unique();
    }
    
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function paginatedCompanyServices(Request $request)
    {
        list($sortName, $sortValue) = parseSearchParam($request);

        $model = $this->services();

        if($request->has('sort') && !is_null($sortValue))
            $model->orderBy($sortName, $sortValue);
        else
            $model->orderBy('services.created_at', 'desc');

        if($request->has('search')){
            $keyword = $request->search;
            $model->where(function ($query) use($keyword) {
                        $query->where('services.name', 'like', '%' . $keyword . '%');
                      });
        }

        if(request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        return $model->paginate($this->paginate);
    }


    public function roles()
    {
        return $this->hasMany(Group::class);
    }

    public function paginatedRoles(Request $request)
    {
        list($sortName, $sortValue) = parseSearchParam($request);

        if(request()->has('default') && request()->default == true) {
            $model = Group::where('company_id', 0);
        } else {
            $model = Group::whereIn('company_id', [0, $this->id])
                    ->whereNull('roles.deleted_at')
                    ->whereNotIn('roles.slug', ['client','manager','member']);
        }

        if($request->has('sort') && !is_null($sortValue))
            $model->orderBy($sortName, $sortValue);
        else
            $model->orderBy('roles.id', 'asc');

        if($request->has('search')){
            $keyword = $request->search;
            $model->where(function ($query) use($keyword) {
                        $query->where('roles.name', 'like', '%' . $keyword . '%')
                              ->orWhere('roles.description', 'like', '%' . $keyword . '%');
                      });
        }

        if(request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        if(request()->has('all') && request()->all == true)
            $data = $model->get();
        else 
            $data = $model->paginate($this->paginate);

        return $data;

    }

    public function companyProjects()
    {
        return $this->hasMany(Project::class);    
    }

    public function projects()
    {
        return $this->companyProjects();

    }

    public function paginatedCompanyProjects(Request $request)
    {
        list($sortName, $sortValue) = parseSearchParam($request);

        $projects = $this->projects();

        // if (!auth()->user()->hasRole('admin|manager')) {
        //     $projects->projectForUserInvolve();
        // }

        $projects->where('type', request()->has('type') ? request()->type : 'project');

        $projects->with([
            'projectManagers.user.meta',
            'projectClient.user.meta',
            'projectMembers.user.meta'
        ]);

        if($request->has('status'))
            $projects->where('status', $request->status);

        if($request->has('sort') && !empty($request->sort))
            $projects->orderBy($sortName, $sortValue);
        else
            $projects->latest();

        if(request()->has('search') && !empty($request->search)) {
            $keyword = request()->search;
            $projects->searchProjects($keyword);
        }

        if(request()->has('per_page') && is_numeric(request()->per_page)) {
            $this->paginate = request()->per_page;
        }

        $data = $projects->paginate($this->paginate);

        $data->map(function ($project) {
            $clientCo = $this->find($project->projectClient->user->props['company_id'] ?? null);
            $project['extra_fields']    = $project->getMeta('extra_fields');
            $project['total_time']      = $project->totalTime();
            $project['progress']        = $project->progress();
            $project['tasks']           = $project->tasks()->count();
            $project['company_name']    = $clientCo ? $clientCo->name : "";
            $project['client_id']       = $project->projectClients->user->id ?? "";
            $project['location']        = $clientCo ? $clientCo->address : "";
            $project['expand']          = false;
            return $project;
        });

        return $data;
    }

    public function allCompanyProjects()
    {
        $data = $this->projects()
                    ->with('milestones')
                    ->get();

        // $data->map(function ($item, $key) {
            
        // });
        
        return $data;
    }

    public function milestones()
    {
        return $this->hasManyThrough(Milestone::class, Project::class);
    }

    public function milestonesID()
    {
        $milestones = $this->milestones;

        $ids = [];

        foreach ($milestones as $value) {
            $ids[] = $value->id;
        }

        return $ids;
    }

    public function tasks()
    {
        $milestones = $this->milestonesID();

        $tasks = Task::whereIn('milestone_id', $milestones);

        return $tasks;
    }

    public function allCompanyPaginatedTasks()
    {
        list($sortName, $sortValue) = parseSearchParam(request());

        $tasks = $this->tasks();

        if(request()->has('sort') && !empty(request()->sort))
            $tasks->orderBy($sortName, $sortValue);

        if(request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        $data = $tasks->paginate($this->paginate);

        if(request()->has('all') && request()->all)
            $data = $tasks->get();

        $data->map(function ($model) {
            $model['total_time'] = $model->total_time();
            $model['assignee_url'] = '';
            if(is_object($model->assigned()->first()))
                $model['assignee_url'] = $model->assigned()->first()->image_url;
        });

        $datus = $data->toArray();

        $datus['counter'] = [
            'open' => $this->taskStatusCounter('open'),
            'behind' => $this->taskStatusCounter('behind'),
            'completed' => $this->taskStatusCounter('completed'),
            'pending' => $this->taskStatusCounter('pending')
        ];

        return $datus;
    }
    
    public function users(){
        return $this->hasManyThrough(TeamMember::class, Team::class);
    }

    public function taskStatusCounter($status)
    {
       return $this->tasks()->where('status', $status)->count();
    }
    
    public function clients()
    {   
        $client_group = $this->clientTeam();

        if( ! $client_group )
            abort(204, 'Team not found!');

        return $client_group->members()
                    ->where('users.deleted_at', null)
                    ->with(['projectsCount'])
                    ->select('users.*');
                    
    }

    public function timeline()
    {
        $members = $this->membersID();

        $activity = Activity::whereIn('causer_id', $members)->with('causer');

        return $activity;
    }

    public function activityLog()
    {
        $query = $this->timeline()->where('log_name', 'system');
        $per_page = request()->has('per_page') ? request()->per_page : $this->paginate;

        if (request()->has('per_page') || request()->has('page')) {
            return $query->latest()->paginate($per_page);
        }

        return $query->latest()->get();
    }

    public function activityLogUnRead()
    {
        $query = $this->timeline()->where('log_name', 'system')->where('read', false);
        $per_page = request()->has('per_page') ? request()->per_page : $this->paginate;

        if (request()->has('per_page') || request()->has('page')) {
            return $query->latest()->paginate($per_page);
        }
        
        return $query->latest()->get();
    }

    public function allTimeline()
    {
        return $this->timeline()
                    ->where('log_name', 'files')
                    ->latest()
                    ->get();
    }

    public function projectTimeline(Project $project)
    {
        return $this->timeline()
                    ->where('subject_type', 'App\Project')
                    ->where('subject_id', $project->id)
                    ->get();
    }

    public function forms()
    {
        $members = $this->membersID();

        return Form::whereHas('user', function ($q) use ($members) {
                   $q->whereIn('id', $members);
               });
    }

    public function paginatedCompanyForms(Request $request)
    {
        list($sortName, $sortValue) = parseSearchParam($request);

        $form = $this->forms();

        if($request->has('sort') && !empty(request()->sort))
            $form->orderBy($sortName, $sortValue);

        if(request()->has('all') && request()->all)
            return $form->get();

        if(request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        return $form->paginate($this->paginate);
    }

    public function allCompanyClients()
    {
        return $this->clients()->get();
    }

    public static function boot() 
    {
        parent::boot();
        
        if(Company::all()->count() > 0) {
            
            Company::created(function ($company) {

                $dashboard = $company->dashboards()->create([
                    'title' => $company->name,
                    'description' => $company->name.' Dashboard'
                ]);

                $company->teams()->create([
                    'name' => $company->name.' Client Team',
                    'company_id' => $company->id,
                    'slug' => 'client-'.$company->id,
                    'description' => 'This is the client team for '. $company->name
                ]);

                $company->teams()->create([
                    'name' => $company->name.' Clients Staffs',
                    'company_id' => $company->id,
                    'slug' => 'client-staffs-'.$company->id,
                    'description' => 'This is the clients staffs team for '. $company->name
                ]);
            });
            
        }
        

        Company::deleting(function($company) {
            foreach(['roles', 'teams'] as $relation)
            {
                foreach($company->{$relation} as $item)
                {
                    $item->delete();
                }
            }
        });
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $users = [];//$this->members->only('first_name', 'last_name', 'email');
        $tasks = [];//$this->tasks->only('title', 'description');
        $projects = [];//$this->companyProjects->only('title', 'description');

        return array_merge( $users, $projects, $tasks );
    }
}
