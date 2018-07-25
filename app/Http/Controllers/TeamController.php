<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\User;
use App\Team;
use App\Company;
use App\Mail\UserCredentials;
use Illuminate\Validation\Rule;
use App\Rules\CollectionUnique;
use Kodeine\Acl\Models\Eloquent\Role;
use Illuminate\Pagination\LengthAwarePaginator;

class TeamController extends Controller
{
    public function index()
    {
        $company = Company::first();

        $result = $company->members()->get();
        return view('pages.team', ['members' => collect($result)]);
    }

    public function memberProfile($id)
    {
        //add some meta
        $user = User::findOrFail($id);
        $user->setMeta('gender', 'Male');
        $user->setMeta('location', 'Iligan');
        $user->setMeta('dob', '17/08/1986');
        $user->setMeta('contact_no', '040-123456789');
        return view('pages.team-profile', ['member' => $user]);
    }

    public function save()
    {
        $company = Auth::user()->company();

        return view('pages.team-new', [
            'roles' => collect($company->roles),
            'action' => 'add'
        ]);
    }

    public function store()
    {
        request()->validate([
            'last_name' => 'required|string',
            'first_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'telephone' => 'required',
            'password' => 'required',
            'group_name' => 'required',
        ]);

        $username = explode('@', request()->email)[0];

        $image_url = 'img/members/alfred.png';

        if(request()->has('image_url'))
            $image_url = request()->image_url;

        $member = User::create([
            'username' => $username,
            'last_name' => request()->last_name,
            'first_name' => request()->first_name,
            'email' => request()->email,
            'telephone' => request()->telephone, 
            'job_title' => request()->job_title,
            'password' => bcrypt(request()->password),
            'image_url' => $image_url
        ]);

        $company = Auth::user()->company();

        $team = $company->teams()
                ->where('slug', strtolower(request()->group_name))
                ->count();

        if(!$team) {
            $company->teams()->create([
                'name' => request()->group_name,
                'description' => request()->group_name.' of '.$company->name,
                'slug' => strtolower(request()->group_name)
            ]);
        }

        $team = $company->teams()
                        ->where('slug', strtolower(request()->group_name))
                        ->first();

        $team->members()->attach($member);

        $role = $company->roles()
                        ->where('slug', strtolower(request()->group_name))
                        ->count();

        if(!$role) {
            $company->roles()->create([
                'name' => request()->group_name,
                'slug' => strtolower(request()->group_name),
                'description' => 'Priveleges of '.request()->group_name
            ]);
        }

        $member->assignRole(strtolower(request()->group_name));

        \Mail::to($member)->send(new UserCredentials($member, request()->password));

        return $member;
    }

    public function update($id)
    {
        $member = User::findOrFail($id);

        request()->validate([
            'username' => 'required|string',
            'last_name' => 'required|string',
            'first_name' => 'required|string',
            'email' => [
                'required',
                 Rule::unique('users')->ignore($member->id)
             ],
            'telephone' => 'required',
            'group_name' => 'required',
        ]);

        $member->first_name = request()->first_name;
        $member->last_name = request()->last_name;
        $member->job_title = request()->job_title; //Added for job_title update 04/07/2018
        $member->email = request()->email;
        $member->telephone = request()->telephone;
        if(request()->has('password'))
            $member->password = request()->password;
        
        $member->save();

        return $member;
    }

    public function delete($id)
    {
        $member = User::findOrFail($id);
        if($member->destroy($id)){
            return response('success', 200);
        }
        else {
            return response('failed', 500);
        }
    }

//Group
    public function groups()
    {
        $roles = Role::paginate(10);

        if(request()->ajax())
            return $roles;
        
        return view('pages.groups');
    }

    public function editgroup($id)
    {
        return Role::findOrFail($id);
    }

    public function updategroup($id)
    {
        $role = Role::findOrFail($id);

        request()->validate([
            'name' => 'required|string'
        ]);

        $role->name = request()->name;

        $role->save();

        return $role;
    }

    public function deletegroup($id)
    {
        $role = Role::findOrFail($id);
        return $role->delete();
    }

    public function role()
    {
        $company = Auth::user()->company();
        $group = $company->roles;
        return response()
            ->json([ 'roles' => $group]);
    }

    public function migrate()
    {
        $to = Role::findOrFail(request()->to);
        $from = Role::findOrFail(request()->from);
        $users = User::where('job_title', $from->name)->get();
        foreach($users as $user){
          $user->job_title = $to->name;
          $user->save();
        }
    }
}
