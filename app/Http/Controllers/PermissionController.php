<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kodeine\Acl\Models\Eloquent\Role;
use Kodeine\Acl\Models\Eloquent\Permission;


class PermissionController extends Controller
{

    public function rolePermissions($role_id)
    {
    	$role = Role::findOrfail($role_id);
    	return response()->json($role->getPermissions());
    }

    public function transferPermissions($role_id)
    {
    	$role = Role::findOrfail($role_id);

    	$permissions = $role->getPermissions();
    	
    	//if done revoke permission of the first role
    	$role->revokeAllPermissions();
    }
}
