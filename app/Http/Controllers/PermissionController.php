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
        $paginate = 10;
        //well change this in the future.
        list($sortName, $sortValue) = parseSearchParam(request());

        $model = auth()->user()->company()->permissions();

        if(request()->has('sort') && !is_null($sortValue))
            $model->orderBy($sortName, $sortValue);
        else
            $model->orderBy('created_at', 'desc');

        if(request()->has('search')){
            $keyword = request()->search;

            $model->where(function ($query) use ($keyword) {
                        $query->where('name', 'like', '%' . $keyword . '%');
                        $query->orWhere('description', 'like', '%' . $keyword . '%');
                        $query->orWhere('create_at', 'like', '%' . $keyword . '%');
                      });
        }

        if(request()->has('per_page'))
            $paginate = request()->per_page;

        return $model->paginate($paginate);
    }

    /*
     * Seed permission or add permission using super admin account
     */
    public function store(PermissionRequest $request)
    {
        $company = auth()->user()->company();

        return $company->permissions()->create(request()->all());
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
