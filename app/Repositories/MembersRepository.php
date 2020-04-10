<?php

namespace App\Repositories;

use App\Company;
use App\Team;
use App\User;

class MembersRepository
{
	protected $company = null;
	protected $pagination = 10;
	protected $teams = ['default','client','staff'];
	protected $hasPagination = false;

	public function __construct(Company $company)
	{
		$this->company = $company;

		if (is_null($company) && auth()->check()) {
			$this->company = auth()->user()->company();
		}

		if (request()->has('per_page')) {
			$this->pagination = request()->per_page;
			$this->hasPagination =  true;
		}
		if (request()->has('per_page')) {
			$this->hasPagination =  true;
		}
	}
	/**
	 * Type : 'default','client','staff', 
	 *
	 */
	public function getTeams()
	{
		return Team::where('company_id', $this->company->id)->get();
	}
	/**
	 * Type : 'default','client','staff', 
	 *
	 */
	public function getTeam($type = 'default')
	{
		if (!in_array($type, ['default','client','staff'])) {
			abort('500', 'Team not found');
		}

		if ($type == 'client') {
			$team = Team::where('company_id', $this->company->id)
				->where('slug', 'like', 'client-%')
				->where('slug', 'not like', 'client-staff%')
				->first();	
		} else {
			$team = Team::where('company_id', $this->company->id)->where('slug', 'like', '%'.$type.'%')->first();
		}
		
		return $team;
	}

	/**
	 * Type : 'clients','managers', 'admins', 'members', 'staff'
	 *
	 */
	public function getUsersByType($type = 'client', $filter = [])
	{
		if (!in_array($type, ['clients','managers', 'admins', 'members', 'client-staffs'])) {
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
                       

		if ($this->hasPagination) {
			return $users->paginate($this->pagination);
		}

		return $users->get();
	}
}
/*
list($sortName, $sortValue) = parseSearchParam(request());
if(request()->has('sort') && !empty(request()->sort))
            $model->orderBy($sortName, $sortValue);*/