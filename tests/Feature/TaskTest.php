<?php

namespace Tests\Feature;

use App\Task;
use App\Milestone;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class TaskTest extends TestCase
{
    public function testTask()
    {
        $this->withoutExceptionHandling();

        $user = User::all()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/task/1');

        dd($response->content());
        $response->assertStatus(200);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStore()
    {   
        $this->withoutExceptionHandling();

        $user = User::find(1);

        $model = Milestone::latest()->first();

        $data = [
            'title' => 'This is a title created in task test.',
            'description' => 'Mocking the description of task test',
            'status' => 'open',
            'days' => 7,
            'role_id' => 4
        ];
        
        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->post('api/milestone/'.$model->id.'/task', $data);

        //dd($response->content());
        $response->assertStatus(201);
    }

    public function testDelete()
    {
        $this->withoutExceptionHandling();

        $user = User::find(1);

        $model = Task::latest()->first();
        
        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->delete('api/task/'.$model->id);

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testTaskMine()
    {
        $this->withoutExceptionHandling();

        $user = User::all()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/task/mine');

       //dd($response->content());
        $response->assertStatus(200);
    }

    public function testTaskIndex()
    {
        $this->withoutExceptionHandling();

        $user = User::all()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/task');

       // dd($response->content());
        $response->assertStatus(200);
    }


    public function testUpdate()
    {
        $this->withoutExceptionHandling();

        $user = User::find(1);

        $model = Task::latest()->first();

        $data = [
            'title' => 'This is a title created in task update.',
            'description' => 'Mocking the description of task test',
            'status' => 'open',
            'days' => 4
        ];
       
        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->put('api/milestone/1/task/'.$model->id, $data);

        //dd($response->content());
        $response->assertStatus(200);
    }
    
	public function testTaskCompany()
	{
        $this->withoutExceptionHandling();

		$task = Task::find(1)->company();

		//dd($task);
		
		$this->assertTrue(true);
	}

    public function testTaskComment()
    {
        $user = User::find(1);
        
        $response = $this->actingAs($user, 'api')
                         ->get('api/task/1/comments');

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testTaskAddComments()
    {
        $user = User::find(1);

        $data = [
            'body' => 'This is a comment'
        ];
        
        $response = $this->actingAs($user, 'api')
                         ->post('api/task/1/comments', $data);

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testTaskStat()
    {
        $user = User::find(1);
        
        $response = $this->actingAs($user, 'api')
                         ->get('api/task/statistics/3');

        //dd($response->exception->validator->messages());
        //dd($response->content());
        $response->assertStatus(200);
    }
}
