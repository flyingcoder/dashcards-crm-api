<?php
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
        factory(App\Project::class, 5)->create()->each(function ($project) {
            $project->members()->attach(1, ['role' => 'Manager']);
            $project->members()->attach(2, ['role' => 'Client']);
            $project->members()->attach(3, ['role' => 'Developers']);
            $project->members()->attach(4);

            $milestone = factory(App\Milestone::class)->create();
            $milestone->tasks()->save(factory(App\Task::class)->create());
            $milestone->tasks()->save(factory(App\Task::class)->create());
            $milestone->tasks()->save(factory(App\Task::class)->create());
            $project->milestones()->save($milestone);

            $milestone2 = factory(App\Milestone::class)->create();
            $milestone2->tasks()->save(factory(App\Task::class)->create());
            $milestone2->tasks()->save(factory(App\Task::class)->create());
            $milestone2->tasks()->save(factory(App\Task::class)->create());
            $project->milestones()->save($milestone2);

            $milestone3 = factory(App\Milestone::class)->create();
            $milestone3->tasks()->save(factory(App\Task::class)->create());
            $milestone3->tasks()->save(factory(App\Task::class)->create());
            $milestone3->tasks()->save(factory(App\Task::class)->create());
            $project->milestones()->save($milestone3);

            $milestone4 = factory(App\Milestone::class)->create();
            $milestone4->tasks()->save(factory(App\Task::class)->create());
            $milestone4->tasks()->save(factory(App\Task::class)->create());
            $milestone4->tasks()->save(factory(App\Task::class)->create());
            $project->milestones()->save($milestone4);
        });

        $tasks = App\Task::all();

        foreach ($tasks as $key => $task) {
            $task->assigned()->attach(rand(1,4));
        }

        $team = App\Team::first();

        $team->members()->attach([4,5,6,7,8,9,10]);
    }
}
