<?php

namespace App\Console\Commands;

use App\Milestone;
use App\Task;
use Illuminate\Console\Command;

class AssignProjectIdToTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign-task-project-id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set project id on tasks based on milestones';

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
        $milestones = Milestone::select('id', 'project_id')->where('project_id', '<>', 0)->get();

        foreach ($milestones as $key => $milestone) {
            Task::where('milestone_id', $milestone->id)->update(['project_id' => $milestone->project_id ]);
        }
    }
}
