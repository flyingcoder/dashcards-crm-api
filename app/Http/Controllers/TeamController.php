<?php

namespace App\Http\Controllers;

use App\Events\NewUserCreated;
use App\Mail\UserCredentials;
use App\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Kodeine\Acl\Models\Eloquent\Role;

class TeamController extends Controller
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        try {
            DB::beginTransaction();
            $validation = [
                'last_name' => 'required|string',
                'first_name' => 'required|string',
                'email' => 'required|email|unique:users',
                'telephone' => 'required',
                'job_title' => 'string',
            ];
            $hasPassword = false;
            if (request()->has('admin_set_password') && request()->admin_set_password) {
                $validation['password'] = 'required|string|min:6|confirmed';
                $hasPassword = true;
            }

            request()->validate($validation);
            $username = explode('@', request()->email)[0];
            $image_url = random_avatar();

            $member = User::create([
                'username' => $username . rand(0, 20),
                'last_name' => request()->last_name,
                'first_name' => request()->first_name,
                'email' => request()->email,
                'telephone' => request()->telephone,
                'job_title' => request()->job_title,
                'password' => $hasPassword ? bcrypt(request()->password) : bcrypt(str_random(12)),
                'image_url' => $image_url,
                'created_by' => auth()->user()->id,
                'props' => [
                    'address' => request()->address ?? 'Unknown',
                    'rate' => request()->rate ?? ''
                ]
            ]);

            $member->setMeta('address', request()->address ?? 'Unknown');
            $member->setMeta('rate', request()->rate ?? '');

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
            $member->tasks = 0;
            $member->projects = 0;

            DB::commit();

            Mail::to($member->email)->send(new UserCredentials($member, request()->password ?? null));
            broadcast(new NewUserCreated($member));

            return $member;
        } catch (Exception $e) {
            DB::rollback();
            $error_code = $e->getCode();
            switch ($error_code) {
                case 1062:
                    return response()->json([ 'message' => 'The company email you have entered is already registered.' ], 500);
                    break;
                case 1048:
                    return response()->json([ 'message' => 'Some fields are missing.' ], 500);
                default:
                    return response()->json([ 'message' => $e->getMessage() ], 500);
                    break;
            }
        }
    }

    /**
     * @param $id
     * @return mixed
     */
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

        $member->setMeta('address', request()->address);

        $member->setMeta('rate', request()->rate);

        $member->week_hours = $member->totalTimeThisWeek();

        return $member;
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function delete($id)
    {
        $member = User::findOrFail($id);
        if ($member->destroy($id)) {
            return response('User is successfully deleted.', 200);
        }
        return response('Failed to delete user.', 500);

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkDelete()
    {
        request()->validate([
            'ids' => 'required|array'
        ]);
        try {
            DB::beginTransaction();
            $members = User::whereIn('id', request()->ids)->get();

            if ($members) {
                foreach ($members as $key => $member) {
                    if (!$member->delete()) {
                        throw new \Exception("Failed to delete user {$member->fullname}!", 1);
                    }
                }
            }
            DB::commit();
            return response()->json(['message' => $members->count() . ' member(s) was successfully deleted'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => "Some user failed to delete"], 500);
        }
    }

    //Group

    /**
     * @return mixed
     */
    public function groups()
    {
        $company = auth()->user()->company();

        $roles = $company->paginatedRoles(request());

        $roles->map(function ($index, $key) {
            $index['group_name'] = $index->name;
        });

        return $roles;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function editgroup($id)
    {
        return Role::findOrFail($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function updategroup($id)
    {
        $role = Role::findOrFail($id);

        $description = isset(request()->description) ? request()->description : '';

        request()->validate([
            'name' => 'required|string'
        ]);

        $role->name = request()->name;
        $role->description = $description;

        if (request()->has('permission_id')) {
            $role->assignPermission(request()->permission_id);
        }

        $role->save();

        return $role;
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function deletegroup($id)
    {

        $role = auth()->user()->company()->roles()->where('id', $id)->firstOrFail();

        try {
            DB::beginTransaction();
            $perms = $role->permissions()->delete();

            $role->delete();

            DB::commit();
            return response('Group was successfully deleted.', 200);
        } catch (Exception $e) {
            DB::rollback();
            return response('Failed to delete group.', 500);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function role()
    {
        $company = Auth::user()->company();
        $group = $company->roles;
        return response()
            ->json(['roles' => $group]);
    }

    /**
     *
     */
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
