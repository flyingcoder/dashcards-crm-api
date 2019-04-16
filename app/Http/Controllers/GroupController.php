<?php

namespace App\Http\Controllers;

use Auth;
use App\Group;
use Kodeine\Acl\Models\Eloquent\Permission;
use Illuminate\Http\Request;
use App\Policies\GroupPolicy;
use Kodeine\Acl\Models\Eloquent\Role;
use Cviebrock\EloquentSluggable\Services\SlugService;

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

        $slug = SlugService::createSlug(Group::class, 'slug', request()->name);

        $description = isset(request()->description) ? request()->description : '' ;

        $role = $company->roles()->create([
                        'name' => request()->name,
                        'slug' => $slug,
                        'description' => $description
                    ]);

        if(request()->has('selected_group')) {

            $copy_role = Role::findOrFail(request()->selected_group);

            $perms = $copy_role->getPermissions();

            foreach ($perms as $key => $value) {

                $parent = Permission::where('name', $key)->first();

                $perm = $company->permissions()->create([
                    'company_id' => $company->id,
                    'name' => $key.'.'.$slug,
                    'slug' => [],
                    'inherit_id' => $parent->id
                ]);

                $role->assignPermission($perm->id);
            }
        }
        
        return $role;
    }
}
