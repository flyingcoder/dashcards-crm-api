<?php

namespace App;

use DB;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Kodeine\Acl\Models\Eloquent\Role;
use Plank\Metable\Metable;
use Spatie\MediaLibrary\Media;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes,
        Metable;
    
    protected $paginate = 10;

    protected $fillable = ['name', 'email', 'domain', 'tag_line', 'short_description', 'long_description'];

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function invoices()
    {
        return $this->members()
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

    public function allPaginatedCalendar(Request $request)
    {
        list($sortName, $sortValue) = parseSearchParam($request);

        $calendars = $this->calendars();

        if($request->has('sort'))
            $calendars->orderBy($sortName, $sortValue);

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

        if($request->has('sort'))
            $invoices->orderBy($sortName, $sortValue);

        return $invoices->paginate($this->paginate);
    }

    public function templates()
    {
        return $this->hasMany(Template::class);
    }

    public function paginatedTemplates(Request $request)
    {
        list($sortName, $sortValue) = parseSearchParam($request);

        $templates = $this->templates();

        if($request->has('type'))
            $templates->where('replica_type', $request->type);

        if($request->has('sort'))
            $templates->orderBy($sortName, $sortValue);

        return $templates->paginate($this->paginate);
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
                       });
    }

    public function allCompanyMembers()
    {
        return $this->members()
                    ->select(
                        'users.id',
                        DB::raw('CONCAT(CONCAT(UCASE(LEFT(users.last_name, 1)), SUBSTRING(users.last_name, 2)), ", ", CONCAT(UCASE(LEFT(users.first_name, 1)), SUBSTRING(users.first_name, 2))) AS name')
                    )->orderBy('users.created_at', 'DESC')
                    ->get();
                    
    }

    public function paginatedCompanyMembers(Request $request)
    {
        return $this->members()
                    ->paginate($this->paginate);
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

        $services = $this->services();

        if($request->has('sort'))
            $services->orderBy($sortName, $sortValue);

        return $services->paginate($this->paginate);
    }

    public function servicesNameList()
    {
        $services = [];
        foreach ($this->members() as $member) {
            if(!empty($member->services)) {
                foreach ($member->services as $service) {
                    $services[] = $service->name;
                }
            }
        }

        return collect($services)->unique('name');
    }


    public function roles()
    {
        return $this->belongsToMany(Role::class);
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

        $projects = $this->projects()->with('tasks');

        if($request->has('status'))
            $projects->where('status', $request->status);

        if($request->has('sort'))
            $projects->orderBy($sortName, $sortValue);
        else
            $projects->latest();

        $data = $projects->paginate($this->paginate);

        $data->map(function ($project) {
            $project['total_time'] = $project->totalTime();
            $project['progress'] = $project->progress();
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

    public function allCompanyPaginatedTasks(Request $request)
    {
        list($sortName, $sortValue) = parseSearchParam($request);

        $tasks = $this->tasks();

        if($request->has('sort'))
            $tasks->orderBy($sortName, $sortValue);

        return $tasks->with('assigned')->paginate($this->paginate);
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

        if($request->has('sort'))
            $clients->orderBy($sortName, $sortValue);
        else
            $clients->latest();

        return $clients->paginate(10);
    }

    public function timeline()
    {
        $members = $this->membersID();

        $activity = Activity::whereHas('causer', function ($q) use ($members) {
               $q->whereIn('id', $members);
            });

        return $activity;
    }

    public function allTimeline()
    {
        return $this->timeline()
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

        if($request->has('sort'))
            $form->orderBy($sortName, $sortValue);

        return $form->paginate($this->paginate);
    }

    public function allCompanyClients()
    {
        return $this->clients()->get();
    }

    public static function boot() 
    {
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
