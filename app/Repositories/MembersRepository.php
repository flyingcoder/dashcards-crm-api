<?php

namespace App\Repositories;

use App\Company;
use App\Group;
use App\Project;
use App\Team;
use App\User;

class MembersRepository
{
    protected $company = null;
    protected $pagination = 12;
    protected $teams = ['default', 'client', 'staff'];
    public $hasPagination = false;

    /**
     * MembersRepository constructor.
     * @param null $company
     */
    public function __construct($company = null)
    {
        $this->company = $company;

        if (is_null($company) && auth()->check()) {
            $this->company = auth()->user()->company();
        }

        if (request()->has('per_page')) {
            $this->pagination = request()->per_page;
            $this->hasPagination = true;
        }
        if (request()->has('per_page')) {
            $this->hasPagination = true;
        }
    }

    /**
     * @param Company $company
     * @return User[]|\Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function companyUserList(Company $company)
    {
        $users = $company->members();

        if (request()->has('withTrashed')) {
            $users = $company->membersWithTrashed();
        }

        $users = $users->with('roles')
            ->select('users.*');

        if ($this->hasPagination) {
            $members = $users->paginate($this->pagination);
            $items = $members->getCollection();

            $data = collect([]);
            foreach ($items as $key => $user) {
                $user->is_company_owner = $user->is_company_owner;
                $user->is_manager = $user->hasRoleLike('manager');
                $user->is_client = $user->hasRoleLike('client');
                $user->is_admin = $user->hasRoleLike('admin');
                $data->push(array_merge($user->toArray()));
            }

            $members->setCollection($data);
            return $members;
        }

        return $users->get();
    }

    /**
     * @param $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * @return mixed
     */
    public function getTeams()
    {
        return Team::where('company_id', $this->company->id)->get();
    }

    /**
     * Type : 'default','client','staff',
     * @param string $type
     * @return
     */
    public function getTeam($type = 'default')
    {
        if (!in_array($type, ['default', 'client', 'staff'])) {
            abort('500', 'Team not found');
        }

        if ($type == 'client') {
            $team = Team::where('company_id', $this->company->id)
                ->where('slug', 'like', 'client-%')
                ->where('slug', 'not like', 'client-staff%')
                ->first();
        } else {
            $team = Team::where('company_id', $this->company->id)->where('slug', 'like', '%' . $type . '%')->first();
        }

        return $team;
    }

    /**
     * Type : 'clients','managers', 'admins', 'members', 'staff'
     * @param string $type
     * @param array $filter
     * @param bool $paginate
     * @return mixed
     */
    public function getUsersByType($type = 'client', $filter = [], $paginate = false)
    {
        if (!in_array($type, ['clients', 'managers', 'admins', 'members', 'client-staffs'])) {
            abort('500', 'User type not found');
        }

        if ($type == 'clients') {
            $team = $this->getTeam('client');
        } elseif ($type == 'client-staffs') {
            $team = $this->getTeam('staff');
        } else {
            $team = $this->getTeam('default');
        }

        $users = User::select('users.*')
            ->join('team_user as tu', 'tu.user_id', '=', 'users.id')
            ->join('teams', 'teams.id', '=', 'tu.team_id')
            ->where('teams.id', $team->id)
            ->whereNull('users.deleted_at');


        if ($this->hasPagination || $paginate) {
            return $users->paginate($this->pagination);
        }

        return $users->get();
    }

    /**
     * @param User $user
     * @param bool $all
     * @return mixed
     */
    public function getUserTeam(User $user, $all = false)
    {
        $teams = Teams::select('teams.*')
            ->join('team_user as tu', 'tu.team_id', '=', 'teams.id')
            ->where('tu.user_id', '=', $user->id);

        return $all ? $teams->get() : $teams->first();
    }

    /**
     * @param \App\Company $company
     * @param Boolean $queryOnly
     * @return Collection of \App\User
     */
    public function getCompanyAdmins(Company $company, $queryOnly = false)
    {
        $ids = Group::where('slug', 'like', '%admin%')->whereIn('company_id', [0, $company->id])->pluck('id')->toArray();

        $members = $company->members()
            ->join('role_user', function ($join) use ($ids) {
                $join->on('users.id', '=', 'role_user.user_id')
                    ->whereIn('role_user.role_id', $ids);
            });

        if (!$queryOnly) {
            $members = $members->select('users.*')->get();
        }

        return $members;
    }

    /**
     * @param \App\Company $company
     * @param Boolean $queryOnly
     * @return Collection of \App\User
     */
    public function getCompanyManagers(Company $company, $queryOnly = false)
    {
        $ids = Group::where('slug', 'like', '%manager%')->where('company_id', $company->id)->pluck('id')->toArray();

        $members = $company->members()
            ->join('role_user', function ($join) use ($ids) {
                $join->on('users.id', '=', 'role_user.user_id')
                    ->whereIn('role_user.role_id', $ids);
            });

        if (!$queryOnly) {
            $members = $members->select('users.*')->get();
        }

        return $members;
    }

    /**
     * @param \App\Project $project
     * @return Collection of \App\User
     */
    public function getProjectClientChatMembers(Project $project)
    {
        $admins = $this->getCompanyAdmins($project->company);
        $managers = $this->getCompanyManagers($project->company);
        $members = $admins->merge($managers);
        $members->push($project->client()->first());
        return $members;
    }

    /**
     * @param \App\Project $project
     * @return Collection of \App\User
     */
    public function getProjectTeamChatMembers(Project $project)
    {
        $admins = $this->getCompanyAdmins($project->company);
        return $admins->merge($project->members);
    }
}
