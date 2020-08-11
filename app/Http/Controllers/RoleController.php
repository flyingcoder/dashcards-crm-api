<?php

namespace App\Http\Controllers;

use App\Permission;
use Kodeine\Acl\Models\Eloquent\Role;

/**
 * Class RoleController
 * @package App\Http\Controllers
 */
class RoleController extends Controller
{

    /**
     * @return mixed
     */
    public function defaultRoles()
    {
        return Role::where('company_id', 0)->get();
    }

    /**
     * @return mixed
     */
    public function companyRoles()
    {
        $roles = auth()->user()->company()->roles;

        if (request()->has('include_admin') && request()->include_admin == true) {
            $admin = Role::where('company_id', 0)->where('slug', 'admin')->first();
            $roles->prepend($admin);
        }

        return $roles;
    }

    /**
     * @param $role_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPermissionByRole($role_id)
    {
        list($sortName, $sortValue) = parseSearchParam(request());

        $role = Role::findOrFail($role_id);

        if (!$role) {
            return response()->json([], 200);
        }

        if ($role->company_id == 0 && $role->slug == 'admin') {
            $permissions = Permission::whereNull('inherit_id')->where('company_id', 0);
        } else {
            $slugs = [];
            foreach ($role->getPermissions() as $key => $slug) {
                $slugs[] = $key . '.' . $role->slug;
            }

            $permissions = Permission::whereIn('name', $slugs)
                ->where('company_id', auth()->user()->company()->id);
        }

        if (request()->has('sort') && !is_null($sortValue))
            $permissions->orderBy($sortName, $sortValue);
        else
            $permissions->orderBy('name', 'ASC');

        $permissions = $permissions->get();
        if ($permissions) {
            foreach ($permissions as $key => $perm) {
                $permissions[$key]->slug = $perm->capability;
            }
        }

        return response()->json(['data' => $permissions], 200);
    }

    /**
     * @param $role_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRolePermissions($role_id)
    {
        request()->validate([
            'role_id' => 'required',
            'permissions' => 'required'
        ]);

        $role = Role::findOrFail($role_id);

        if (!$role) {
            return response()->json([
                'message' => "Can't find role!",
                'type' => 'error'
            ], 200);
        }

        foreach (request()->permissions as $key => $permission) {
            $perm = Permission::findOrFail($permission['id']);
            $perm->slug = $permission['slug'];
            $perm->save();
        }

        return response()->json([
            'message' => "Permission for role " . $role->name . " successfully updated",
            'type' => 'success'
        ], 200);
    }
}