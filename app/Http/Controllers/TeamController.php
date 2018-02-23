<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\User;
use App\Team;
use App\Company;
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
            'name' => 'required|string',
            'group_id' => 'required',
            'job_title' => 'required',
            'email' => 'required|email|unique:users',
            'telephone' => 'required',
            'password' => 'required|confirmed',
        ]);

        $project = User::create([
            'name' => request()->name,
            'job_title' => request()->job_title,
            'email' => request()->email,
            'telephone' => request()->telephone,
            'password' => bcrypt(request()->password),
            'created_by' => Auth::user()->name,
            'image_url' => 'img/members/alfred.png'
        ]);

        request()->session()->flash('message.level', 'success');
        request()->session()->flash('message.content', 'Member was successfully added!');

        return back();
    }

//Group
    public function groups()
    {
        $roles = Role::paginate(5);

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
        return $role->destroy($id);
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
