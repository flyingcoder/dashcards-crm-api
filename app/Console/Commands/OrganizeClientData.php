<?php

namespace App\Console\Commands;

use App\Group;
use App\User;
use Illuminate\Console\Command;

/**
 * Class OrganizeClientData
 * @package App\Console\Commands
 */
class OrganizeClientData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clients:organize-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reorganized client data (run one time only)';

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
            $user->companies()->attach($company->id, ['type' => 'main']);

            $props = $user->props;
            $company_id = $props['company_id'] ?? false;

            if ($company_id && $company_id != $company->id) {
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
