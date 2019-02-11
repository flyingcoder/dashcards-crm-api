<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kodeine\Acl\Models\Eloquent\Role;
use Kodeine\Acl\Models\Eloquent\Permission;
use App\Http\Requests\PermissionRequest;


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
}
