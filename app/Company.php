<?php

namespace App;

use DB;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Kodeine\Acl\Models\Eloquent\Role;
use Spatie\MediaLibrary\Media;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class Company extends Model
{
    protected $paginate = 10;

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function invoices()
    {
        return $this->members()
                    ->join('invoices', 'invoices.user_id', '=', 'users.id');
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
            $templates->orderBy($sortName, $sortValue);

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
        return $this->members()->get();
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
                        'services.name as service_name', 
                        'services.created_at as service_created_at',
                        'member.name as user_name');
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
        return $this->hasMany(Role::class);
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
                 ->with('milestones')
                 ->select(
                    DB::raw('CONCAT(manager.last_name, ", ", manager.first_name) AS manager_name'),
                    'client.image_url as client_image_url',
                    DB::raw('CONCAT(client.last_name, ", ", client.first_name) AS client_name'),
                    'projects.*',
                    'services.name as service_name'
                 )->where('projects.deleted_at', null);

        return $projects;
    }

    public function paginatedCompanyProjects(Request $request)
    {
        list($sortName, $sortValue) = parseSearchParam($request);

        $projects = $this->projects();

        if($request->has('status'))
            $projects->where('status', $request->status);

        if($request->has('sort'))
            $projects->orderBy($sortName, $sortValue);

        return $projects->paginate($this->paginate);
    }

    public function allCompanyProjects()
    {
        return $this->projects()
                    ->get();
    }

    public function milestones()
    {
        return $this->hasManyThrough(Milestone::class, Project::class);
    }

    public function tasks()
    {
        return $this->milestones()
                           ->join('tasks', 'tasks.milestone_id', '=', 'milestones.id')
                           ->where('tasks.deleted_at', null);
    }

    public function allCompanyPaginatedTasks(Request $request)
    {
        list($sortName, $sortValue) = parseSearchParam($request);

        $tasks = $this->tasks();

        if($request->has('sort'))
            $tasks->orderBy($sortName, $sortValue);

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
                        'company.value as company_name',
                        'status.value as status',
                        'users.*'
                    )->with(['projectsCount'])
                    ->where('users.deleted_at', null);
                    
    }

    public function paginatedCompanyClients(Request $request)
    {
        list($sortName, $sortValue) = parseSearchParam($request);

        return $this->clients()
                    ->orderBy($sortName, $sortValue)
                    ->paginate(10);
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
