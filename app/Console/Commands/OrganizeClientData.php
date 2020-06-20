<?php

namespace App\Console\Commands;

use App\Company;
use App\User;
use Illuminate\Console\Command;

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users =  User::withTrashed()->whereHasMeta('company_name')->get();
        foreach ($users as $key => $user) {
            $metas = $user->getAllMeta();
            if ($metas->get('company_name', false)) {
                $company = Company::create([
                    'name' => $metas->get('company_name'),
                    'is_private' => 1,
                    'address' => $metas->get('location', null),
                    'email' => $metas->get('company_email', null),
                    'created_at' => now()->format('Y-m-d H:i:s'),
                    'others' => [
                            'contact_name' => $metas->get('contact_name', null)
                        ]
                ]);
                $props = $user->props;
                $props['location'] = $metas->get('location', null);
                $props['company_id'] = $company->id;
                $props['status'] = $metas->get('status', 'Active');
                $user->props = $props;
                $user->created_by = $user->created_by ?? $metas->get('created_by', null);
                if($user->save()){
                    if($user->hasMeta('status')) $user->removeMeta('status');
                    if($user->hasMeta('location')) $user->removeMeta('location');
                    if($user->hasMeta('company_name')) $user->removeMeta('company_name');
                    if($user->hasMeta('company_email')) $user->removeMeta('company_email');
                    if($user->hasMeta('company_tel')) $user->removeMeta('company_tel');
                }
            }
        }
        echo "done";
    }
}
