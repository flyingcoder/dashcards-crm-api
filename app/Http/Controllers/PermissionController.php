<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use App\User;
use Illuminate\Http\Request;
use Kodeine\Acl\Models\Eloquent\Permission;
use Kodeine\Acl\Models\Eloquent\Role;


class PermissionController extends Controller
{
    public function index()
    {
        $company = auth()->user()->company();

        return $company->paginatedPermissions();
    }

    /*
     * Seed permission or add permission using super admin account
     */
    public function store(PermissionRequest $request)
    {
        $company = auth()->user()->company();

        $parentPerm = Permission::findOrfail(request()->permission_id);

        $description = ucfirst($company->name). "custom permission";

        if(request()->has('description'))
            $description = request()->description;

        $data = [
            'name' => $parentPerm->name.'.'.request()->group,
            'slug' => request()->slug,
            'inherit_id' => $parentPerm->getKey(),
            'description' => $description
        ];

        return $company->permissions()->create($data);
    }

    public function search()
    {
        $query = request()->q;

        $result = Permission::where(function($q) use ($query) {
                              $q->where('permissions.name', 'LIKE', "%{$query}%")
                                ->where('permissions.inherit_id', null);
                      });

        return $result->get();
    }

    public function update($id)
    {
        $permission = Permission::findOrfail($id);

        $permission->update(request()->all());

        return $permission;
    }

    public function delete($id)
    {
        $model = Permission::findOrfail($id);

        if($model->destroy($id)){
            return response('Permission is successfully deleted.', 200);
        } else {
            return response('Failed to delete permission.', 500);
        }
    }

    public function permissions($role_id)
    {
    	$role = Role::findOrfail($role_id);
    	return $role->getPermissions();
    }

    public function transferPermissions($role_id)
    {
    	$role = Role::findOrfail($role_id);

    	$permissions = $role->getPermissions();
    	
    	//if done revoke permission of the first role
    	$role->revokeAllPermissions();
    }

    public function defaultPermissions()
    {
        return Permission::whereNull('inherit_id')->where('company_id', 0)->get();
    }

    public function userPermissions($id)
    {
        $user = User::findOrfail($id);

        return response()->json($user->getPermissions(), 200);
    }
}
