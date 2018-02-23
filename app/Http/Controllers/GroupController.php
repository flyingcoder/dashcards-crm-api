<?php

namespace App\Http\Controllers;

use Auth;
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

    public function store()
    {
    	//(new GroupPolicy())->create();

        $company = Auth::user()->company();

        request()->validate([
            'name' => 'required',
        ]);

        $slug = SlugService::createSlug(Role::class, 'slug', request()->name);

        $company->roles()->create([
            'name' => request()->name,
            'slug' => $slug,
        ]);

        return response()
                ->json(['message' => 'Group was successfully added.']);
    }
}
