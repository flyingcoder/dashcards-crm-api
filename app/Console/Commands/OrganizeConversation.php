<?php

namespace App\Console\Commands;

use App\Conversation;
use App\Project;
use Illuminate\Console\Command;

class OrganizeConversation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'organize:conversations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Re organize conversations data including fixing members in conversations in type (group, team and client)';

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
        $conversations = Conversation::whereIn('type', ['group', 'client', 'team'])->get();
        foreach ($conversations as $convo) {
            if ($convo->type == 'group') {
                $props = $convo->data;
                if (isset($props['group_creator']['id'])) {
                    $props = [
                        'company' => $props['company_id'] ?? null,
                        'group_name' => $props['group_name'] ?? 'Private Group Chat',
                        'group_creator' => $props['group_creator']['id'] ?? 'system'
                    ];
                }
                $convo->data = $props;
                $convo->save();
            } elseif ($convo->type == 'team' || $convo->type == 'client') {
                $project = Project::withTrashed()->find($convo->project_id);
                $convo->data = [
                    'company' => $project->company->id,
                    'group_name' => $project->title . ' ' . ucwords($convo->type) . ' Group Chat',
                    'group_creator' => 'system'
                ];
                $convo->save();
                $project->updateTeamProjectRoomUsers();
                $project->updateClientProjectRoomUsers();
            }
        }
        echo 'done!';
    }
}
