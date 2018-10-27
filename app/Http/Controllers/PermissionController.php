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
        return Permission::all();
    }

    /*
     * Seed permission or add permission using super admin account
     */
    public function store(PermissionRequest $request)
    {
        $permission = new Permission();

        return $permission->create(request()->all());
    }

    public function update($id)
    {
        $permission = Permission::findOrfail($id);

        return (int) $permission->update(request()->all());
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
