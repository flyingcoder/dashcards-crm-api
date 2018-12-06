<?php
use App\Milestone;
use Illuminate\Database\Seeder;

class ProjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //project seeder
        factory(App\Project::class, 10)->create()->each(function ($project) {
            $project->members()->attach(1, ['role' => 'Manager']);
            $project->members()->attach(3, ['role' => 'Client']);
            //$project->members()->attach(3, ['role' => 'Developers']);

            for($i=0;$i<10;$i++) {
                $project->milestones()
                    ->save(
                        factory(App\Milestone::class)
                        ->make()
                    );
            }
        });

        $milestones = Milestone::all();

        foreach ($milestones as $milestone) {
            for($i=0;$i<10;$i++) {
                $milestone->tasks()
                          ->save(factory(App\Task::class)
                            ->make()
                        );
            }
        }

        $tasks = App\Task::all();

        foreach ($tasks as $key => $task) {
            $task->assigned()->attach(rand(1,4));
        }

        $team = App\Team::first();

        $team->members()->attach([4,5,6,7,8,9,10]);
    }
}
