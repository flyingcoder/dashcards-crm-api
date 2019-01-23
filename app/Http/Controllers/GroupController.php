<?php

namespace App\Http\Controllers;

use Auth;
use App\Group;
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

        if(request()->has('permission_id'))
            $role->assignPermission(request()->permission_id);

        unset($role->permissions);

        $role->permission_id = request()->permission_id;
        
        return $role;
    }
}
