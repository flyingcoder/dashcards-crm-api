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
        // $company = auth()->company();

        //return $company->paginatedTemplates();
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
        try {
            request()->validate([
                'last_name' => 'required|string',
                'first_name' => 'required|string',
                'email' => 'required|email|unique:users',
                'telephone' => 'required',
                // 'password' => 'required|confirmed'
            ]);

            $username = explode('@', request()->email)[0];

            $image_url = env('APP_URL').'/img/members/alfred.png';

            $member = User::create([
                'username' => $username.rand(0, 20),
                'last_name' => request()->last_name,
                'first_name' => request()->first_name,
                'email' => request()->email,
                'telephone' => request()->telephone,
                'job_title' => request()->job_title,
                'password' => bcrypt(str_random(12)), //set random password
                'image_url' => $image_url,
                'created_by' => auth()->user()->id,
                'code' => generateSetPasswordCode()
            ]);

            $company = auth()->user()->company();

            $role = request()->group_name;

            $team = $company->defaultTeam();

            if (auth()->user()->hasRole('client')) {
                $team = $company->clientStaffTeam();

                $role = 'client';
            }

            $team->members()->attach($member);

            $member->assignRole($role);

            $member->group_name = $role;

            \Mail::to($member->email)->send(new UserCredentials($member));
            //\Mail::to($member)->send(new UserCredentials($member, request()->password));

            unset($member->tasks);
            unset($member->projects);

            $member->tasks = $member->tasks()->count();

            $member->projects = $member->projects()->count();

            return $member;
        } catch (Exception $e) {
            $error_code = $e->errorInfo[1];

            switch ($error_code) {
                case 1062:
                    return response()->json([
                                'error' => 'The company email you have entered is already registered.'
                           ], 500);
                    break;
                case 1048:
                    return response()->json([
                                'error' => 'Some fields are missing.'
                           ], 500);
                default:
                    return response()->json([
                                'error' => $e."test"
                           ], 500);
                    break;
            }
        }
    }

    public function update($id)
    {
        $member = User::findOrFail($id);

        request()->validate([
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

        if (request()->has('password')) {
            $member->password = request()->password;
        }

        $member->revokeAllRoles();

        $member->assignRole(request()->group_name);
        
        $member->save();

        $member->group_name = request()->group_name;

        if (auth()->user()->id == $member->id) {
            $member->can = $member->getPermissions();
        }

        unset($member->projects);

        unset($member->tasks);

        $member->tasks = $member->tasks()->count();

        $member->projects = $member->projects()->count();

        return $member;
    }

    public function delete($id)
    {
        $member = User::findOrFail($id);
        if ($member->destroy($id)) {
            return response('User is successfully deleted.', 200);
        } else {
            return response('Failed to delete user.', 500);
        }
    }

    //Group
    public function groups()
    {
        $company = auth()->user()->company();

        $roles = $company->paginatedRoles(request());

        $roles->map(function ($index, $key) {
            $index['group_name'] = $index->name;
        });

        return $roles;
    }

    public function editgroup($id)
    {
        return Role::findOrFail($id);
    }

    public function updategroup($id)
    {
        $role = Role::findOrFail($id);

        $description = isset(request()->description) ? request()->description : '' ;

        request()->validate([
            'name' => 'required|string'
        ]);

        $role->name = request()->name;
        $role->description = request()->description;

        if (request()->has('permission_id')) {
            $role->assignPermission(request()->permission_id);
        }

        $role->save();

        return $role;
    }

    public function deletegroup($id)
    {
        $role = Role::findOrFail($id);

        if ($role->destroy($id)) {
            return response('Group is successfully deleted.', 200);
        } else {
            return response('Failed to delete group.', 500);
        }
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
        foreach ($users as $user) {
            $user->job_title = $to->name;
            $user->save();
        }
    }
}
