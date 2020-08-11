<?php

namespace App\Console\Commands;

use App\Group;
use App\User;
use Illuminate\Console\Command;


/**
 * Class OrganizeUserData
 * @package App\Console\Commands
 */
class OrganizeUserData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:organize-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reorganized user data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     *
     */
    public function handle()
    {
        $users = User::withTrashed()->get();
        foreach ($users as $key => $user) {
            $company = $user->company();
            if (!$user->companies()->where('id', $company->id)->exists())
                $user->companies()->attach($company->id, ['type' => 'main']);

            $props = $user->props;
            $company_id = $props['company_id'] ?? false;

            if ($company_id && $company_id != $company->id) {
                if (!$user->companies()->where('id', $company_id)->exists())
                    $user->companies()->attach($company_id, ['type' => 'client']);
            }
            $props['company_id'] = $company->id;
            $props['rate'] =  $props['rate'] ?? '';
            $props['location'] = $props['location'] ?? '';
            $user->props = $props;
            $user->save();
        }

        $this->updateRoleDescriptions();
        echo "done";
    }

    /**
     *
     */
    public function updateRoleDescriptions()
    {
        $desc = [
            'Admin' => 'Admin has the highest level of access of app permissions and privileges',
            'Manager' => 'Role for those who handle managerial tasks',
            'Client' => 'Role for those business owners, representatives or agents',
            'Member' => 'Role for those company workers, guests or employees'
        ];
        $group = Group::whereIn('name', array_keys($desc))->get();
        foreach ($group as $role) {
            $role_desc = $desc[$role->name] ?? '';
            $role->description = $role_desc;
            $role->save();
        }
    }
}
