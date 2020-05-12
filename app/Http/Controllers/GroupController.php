<?php

namespace App\Http\Controllers;

use App\Group;
use App\Permission;
use App\Policies\GroupPolicy;
use App\User;
use Auth;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Kodeine\Acl\Models\Eloquent\Role;

class GroupController extends Controller
{
    public function members($role_id)
    {
    	$role = Role::findOrFail($role_id);

    	return $role->users()->paginate(10);
    }

    public function assignPermission($id)
    {
        $role = Role::findOrFail($id);

        if(is_array(request()->permission_id)) {
            foreach (request()->permission_id as $key => $value) {
                $role->assignPermission($value);
            }
        } else {
            return response('Expected `permission_id` to be an array.', 500);
        }

        return $role->getPermissions();
    }

    public function store()
    {
    	//(new GroupPolicy())->create();

        $company = Auth::user()->company();

        request()->validate([
            'name' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $slug = SlugService::createSlug(Group::class, 'slug', request()->name);

            $description = isset(request()->description) ? request()->description : '' ;

            if(request()->has('selected_group') && !empty(request()->selected_group)) {

                $role = $company->roles()->create([
                            'name' => request()->name,
                            'slug' => $slug,
                            'description' => $description,
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                        ]);

                $copy_role = Role::findOrFail(request()->selected_group);

                $perms = $copy_role->permissions;

                foreach ($perms as $key => $perm) {
                    $key_prefix = explode('.', $perm->name)[0];
                    $perm = $company->permissions()->create([
                        'company_id' => $company->id,
                        'name' => $key_prefix.'.'.$slug,
                        'slug' => $perm->slug ,
                        'inherit_id' => $perm->inherit_id,
                        'description' => Auth::user()->company()->name." ".request()->name." Permissions"
                    ]);
                    if ($perm) {
                        $role->assignPermission($perm->id);
                    }
                }
            }
            
            DB::commit();

            $rol = Role::findOrFail($role->id);

            $rol->permissions;
            
            return $rol;
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'type' => 'error'
            ], 500 );
        }
    }

    public function updateRoles()
    {
        request()->validate([
            'user' => 'required|exists:users,id',
            'roles' => 'required|array'
        ]);

        $user = User::findOrFail(request()->user);

        $user->syncRoles(request()->roles);

        
        $user = $user->fresh();
        $user->load('roles');
        $user->is_company_owner = $user->is_company_owner;
        $user->is_manager = $user->hasRoleLike('manager');
        $user->is_client = $user->hasRoleLike('client');
        $user->is_admin = $user->hasRoleLike('admin');

        return response()->json($user->toArray(), 200);
    }
}
