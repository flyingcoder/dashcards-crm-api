<?php

namespace App;

use DB;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Kodeine\Acl\Models\Eloquent\Role;
use Kodeine\Acl\Models\Eloquent\Permission;
use Plank\Metable\Metable;
use Spatie\MediaLibrary\Media;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class Company extends Model
{
    use SearchableTrait,
        SoftDeletes,
        Metable;

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
        
        'columns' => [
            'users.first_name' => 10,
            'users.last_name' => 10,
            'users.bio' => 2,
            'users.email' => 5,
            'posts.title' => 2,
            'posts.body' => 1,
        ],*/
    ];
    
    protected $paginate = 10;

    protected $fillable = ['name', 'email', 'domain', 'tag_line', 'short_description', 'long_description'];

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

        $model->with('causer');

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
            'description' => request()->description,
            'url' => request()->url
        ]);
    }

    public function reports()
    {
        return $this->hasMany(Reports::class);
    }

    public function paginatedPermissions()
    {
        list($sortName, $sortValue) = parseSearchParam(request());

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

        $data = $model->paginate($this->paginate);

        if(request()->has('all') && request()->all)
            $data = $model->get();

        return $data;
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

    public function allCompanyInvoices()
    {
        return $this->invoices()->get();
    }

    public function paginatedCompanyInvoices(Request $request)
    {
        list($sortName, $sortValue) = parseSearchParam($request);

        $invoices = $this->invoices();

        if($request->has('sort') && !empty(request()->sort))
            $invoices->orderBy($sortName, $sortValue);

        if(request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        return $invoices->paginate($this->paginate);
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
        return $this->members()
                    ->select(
                        'users.id',
                        'users.job_title',
                        'users.email',
                        'users.first_name',
                        'users.last_name',
                        'users.image_url'
                    )
                    ->with('tasks', 'projects')
                    ->orderBy('users.created_at', 'DESC')
                    ->get();
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

    public function allCompanyMembers()
    {
        $model = $this->members()
                    ->select(
                        'users.id',
                        'users.is_online',
                        DB::raw('CONCAT(CONCAT(UCASE(LEFT(users.last_name, 1)), SUBSTRING(users.last_name, 2)), ", ", CONCAT(UCASE(LEFT(users.first_name, 1)), SUBSTRING(users.first_name, 2))) AS name')
                    )->orderBy('users.created_at', 'DESC');
                    
        if( request()->has('online') && request()->online )
            $model->where('is_online', 1);

        return $model->get();
                    
    }

    public function paginatedCompanyMembers(Request $request)
    {
         list($sortName, $sortValue) = parseSearchParam($request);

        $members = $this->members()
                        ->select(
                            'users.id',
                            'users.job_title',
                            'users.email',
                            'users.first_name',
                            'users.last_name',
                            'users.image_url',
                            'users.telephone'
                        )->with('tasks', 'projects', 'teams');

        if($request->has('sort') && !empty(request()->sort))
            $members->orderBy($sortName, $sortValue);
        else
            $members->orderBy('users.created_at', 'DESC');

        if(request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        return $members->paginate($this->paginate);
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
        $data = [];

        $members = $this->membersID();

        $services = Service::whereIn('user_id', $members)
                            ->select('name')
                            ->get();

        foreach ($services as $key => $value) {
            $data[] = $value->name;
        }

        return collect($data)->unique();
    }

    public function services()
    {
        $members = $this->membersID();
        
        return Service::whereIn('user_id', $members)
                      ->where('services.deleted_at', null)
                      ->join('users as member', 'member.id', '=', 'services.user_id')
                      ->select(
                        'services.id as id',
                        DB::raw('CONCAT(UCASE(LEFT(services.name, 1)), SUBSTRING(services.name, 2)) as service_name'), 
                        'services.created_at as service_created_at',
                        DB::raw('CONCAT(CONCAT(UCASE(LEFT(member.last_name, 1)), SUBSTRING(member.last_name, 2)), ", ", CONCAT(UCASE(LEFT(member.first_name, 1)), SUBSTRING(member.first_name, 2))) AS name'));
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
        return $this->belongsToMany(Role::class);
    }

    public function paginatedRoles(Request $request)
    {
        list($sortName, $sortValue) = parseSearchParam($request);

        $model = $this->roles();

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

        return $model->paginate($this->paginate);
    }

    public function projects()
    {
        $members = $this->membersID();

        $projects = Project::whereHas('manager', function ($q) use ($members) {
               $q->whereIn('id', $members);
            });

        $projects->join('services', 'services.id', '=', 'projects.service_id')
                 ->join('project_user as manager_pivot', function ($join) {
                    $join->on('manager_pivot.project_id', '=', 'projects.id')
                         ->where('manager_pivot.role', '=', 'Manager');
                 })
                 ->join('users as manager', 'manager_pivot.user_id', '=', 'manager.id')
                 ->join('project_user as client_pivot', function ($join) {
                    $join->on('client_pivot.project_id', '=', 'projects.id')
                         ->where('client_pivot.role', '=', 'Client');
                 })
                 ->join('users as client', 'client_pivot.user_id', '=', 'client.id')
                 ->select(
                    'client.id AS client_id',
                    'services.id AS service_id',
                    'manager.id AS manager_id',
                    DB::raw('CONCAT(CONCAT(UCASE(LEFT(manager.last_name, 1)), SUBSTRING(manager.last_name, 2)), ", ", CONCAT(UCASE(LEFT(manager.first_name, 1)), SUBSTRING(manager.first_name, 2))) AS manager_name'),
                    'client.image_url as client_image_url',
                    DB::raw('CONCAT(CONCAT(UCASE(LEFT(client.last_name, 1)), SUBSTRING(client.last_name, 2)), ", ", CONCAT(UCASE(LEFT(client.first_name, 1)), SUBSTRING(client.first_name, 2))) AS client_name'),
                    'projects.*',
                    DB::raw('CONCAT(UCASE(LEFT(services.name, 1)), SUBSTRING(services.name, 2)) as service_name')
                 )->where('projects.deleted_at', null);

        return $projects;
    }

    public function paginatedCompanyProjects(Request $request)
    {
        list($sortName, $sortValue) = parseSearchParam($request);

        $projects = $this->projects()->with(['tasks', 'members']);

        if($request->has('status'))
            $projects->where('status', $request->status);

        if($request->has('sort') && !empty($request->sort))
            $projects->orderBy($sortName, $sortValue);
        else
            $projects->latest();

        if(request()->has('search') && !empty($request->search)) {

            $table = 'projects';

            $keyword = request()->search;

            $projects->where(function ($query) use ($keyword, $table) {
                        $query->where("{$table}.title", "like", "%{$keyword}%")
                              ->orWhere("client.first_name", "like", "%{$keyword}%")
                              ->orWhere("client.last_name", "like", "%{$keyword}%")
                              ->orWhere("services.name", "like", "%{$keyword}%")
                              ->orWhere("{$table}.status", "like", "%{$keyword}%")
                              ->orWhere("manager.first_name", "like", "%{$keyword}%")
                              ->orWhere("manager.last_name", "like", "%{$keyword}%");
                  });
        }

        if(request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        $data = $projects->paginate($this->paginate);

        $data->map(function ($project) {
            $project['total_time'] = $project->totalTime();
            $project['progress'] = $project->progress();
            $tasks = $project->tasks->count();
            unset($project['tasks']);
            $project['tasks'] = $tasks;
            return $project;
        });

        return $data;
    }

    public function allCompanyProjects()
    {
        return $this->projects()
                    ->with('milestones')
                    ->get();
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

        $tasks->with('assigned');

        if(request()->has('all') && request()->all)
            return $tasks->get();

        if(request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        return $tasks->paginate($this->paginate);
    }

    public function clients()
    {   
        $client_group = $this->teams()->where('slug', 'clients')->first();
        
        if( ! $client_group )
            abort(204, 'Team not found!');

        return $client_group->members()
                    ->join('meta as company', function ($join) {
                       $join->on('company.metable_id', '=', 'users.id')
                            ->where('company.key', 'company_name');
                    })->join('meta as status', function ($join) {
                       $join->on('status.metable_id', '=', 'users.id')
                            ->where('status.key', 'status');
                    })->select(
                        DB::raw('CONCAT(CONCAT(UCASE(LEFT(users.last_name, 1)), SUBSTRING(users.last_name, 2)), ", ", CONCAT(UCASE(LEFT(users.first_name, 1)), SUBSTRING(users.first_name, 2))) AS full_name'),
                        'company.value as company_name',
                        'status.value as status',
                        'users.*'
                    )->with(['projectsCount'])
                    ->where('users.deleted_at', null);
                    
    }

    public function paginatedCompanyClients(Request $request)
    {
        list($sortName, $sortValue) = parseSearchParam($request);

        $clients = $this->clients();

        $clients->latest();

        if($request->has('sort') && !empty(request()->sort))
            $clients->orderBy($sortName, $sortValue);
        
        if(request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        return $clients->paginate($this->paginate);
    }

    public function timeline()
    {
        $members = $this->membersID();

        $activity = Activity::whereIn('causer_id', $members);

        return $activity;
    }

    public function allTimeline()
    {
        return $this->timeline()
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
        if(Company::all()->count() > 0) {
            
            Company::created(function ($company) {

                $dashboard = $company->dashboards()->create([
                    'title' => $company->name,
                    'description' => $company->name.' Dashboard'
                ]);

                $role = new Role();

                $roleAdmin = $role->create(
                    [
                        'name' => 'Administrator',
                        'slug' => 'default-admin-'.$company->id,
                        'description' => 'manage administration privileges',
                    ]
                );

                $roleClient = $role->create(
                    [
                        'name' => 'Client',
                        'slug' => 'client-'.$company->id,
                        'description' => 'Client privileges',
                    ]
                );

                $roleManager = $role->create(
                    [
                        'name' => 'Manager',
                        'slug' => 'manager-'.$company->id,
                        'description' => 'manage a team privileges',
                    ]
                );

                $company->roles()->attach([
                    $roleAdmin->id,
                    $roleClient->id,
                    $roleManager->id
                ]);

                $company->teams()->create([
                    'name' => $company->name.' Default Team',
                    'company_id' => $company->id,
                    'slug' => 'default-'.$company->id,
                    'description' => 'This is the default team for a company'
                ]);

                $company->teams()->create([
                    'name' => $company->name.' Client Team',
                    'company_id' => $company->id,
                    'slug' => 'client-'.$company->id,
                    'description' => 'This is the client team for a company'
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

}
