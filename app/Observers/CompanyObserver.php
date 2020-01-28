<?php

namespace App\Observers;

use App\Company;
use App\Group;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Kodeine\Acl\Models\Eloquent\Permission;
use Kodeine\Acl\Models\Eloquent\Role;
use Loggy;

class CompanyObserver
{
    /**
     * Handle the company "created" event.
     *
     * @param  \App\Company  $company
     * @return void
     */
    public function created(Company $company)
    {
        //create duplicate role except admin , admin/super admin permissions cant be edited
        $defaultRoles = Role::where('company_id', 0)->whereNotIn('slug', ['admin', 'superadmin'])->get();

        foreach ($defaultRoles as $key => $role) {
            $unique_slug = SlugService::createSlug(Group::class, 'slug', $company->name.' '.$role->name);

            $replicated_role = $company->roles()->create([
                        'name' => $role->name,
                        'slug' => $unique_slug,
                        'description' => $role->description,
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ]);

            $perms = $role->getPermissions();
            foreach ($perms as $key2 => $value) {
                $parent = Permission::where('name', $key2.'.'.$role->slug)->first();
                // Loggy::write('event', json_encode([$parent, $key2.'.'.$role->slug]));
                if ($parent) {
                    $perm = $company->permissions()->create([
                            'company_id' => $company->id,
                            'name' => $key2.'.'.$unique_slug,
                            'slug' => $parent->slug,
                            'inherit_id' => $parent->inherit_id,
                            'description' => $company->name." ".$replicated_role->name." Permissions"
                        ]);
                    $replicated_role->assignPermission($perm->id);
                }
            }
        }
    }

    /**
     * Handle the company "updated" event.
     *
     * @param  \App\Company  $company
     * @return void
     */
    public function updated(Company $company)
    {
        //
    }

    /**
     * Handle the company "deleted" event.
     *
     * @param  \App\Company  $company
     * @return void
     */
    public function deleted(Company $company)
    {
        //
    }

    /**
     * Handle the company "restored" event.
     *
     * @param  \App\Company  $company
     * @return void
     */
    public function restored(Company $company)
    {
        //
    }

    /**
     * Handle the company "force deleted" event.
     *
     * @param  \App\Company  $company
     * @return void
     */
    public function forceDeleted(Company $company)
    {
        //
    }
}
