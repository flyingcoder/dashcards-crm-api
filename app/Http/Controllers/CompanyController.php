<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    protected  $types = [
            'manager',
            'client',
            'member',
            'agent'
        ];

    public function members()
    {
        if (request()->has('type') && in_array(request()->type, $this->types)) {
            $type = trim(request()->type);
            return auth()->user()->company()
                ->members()
                ->select('users.*')
                ->with('roles')
                ->whereHas('roles', function (Builder $query) use ($type) {
                        $query->where('slug', 'like', "%{$type}%");
                })->get();
            
        }
    	return auth()->user()->company()->allCompanyMembers();
    }

    public function teams()
    {
        if(auth()->user()->hasRole('client'))
            return auth()->user()->clientStaffs();

    	if(request()->has('all') && request()->all)
            return auth()->user()->company()->allTeamMembers();
    	
        return auth()->user()->company()->paginatedCompanyMembers();
    }

    public function member($id)
    {
    	$user = User::findOrFail($id);

        $user->getAllMeta();

        $user['week_hours'] = $user->totalTimeThisWeek();
        
        $roles = $user->roles()->first();
        if(!is_null($roles))
            $user['group_name'] = $roles->id;

        $user->tasks;

        return $user;
    }

    public function invoices()
    {
        return auth()->user()->company()->invoices()->get();
    }
}
