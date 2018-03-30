<?php

namespace Tests\Feature;

use App\Task;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class TaskTest extends TestCase
{

	public function testTaskCompany()
	{
		$task = Task::find(1)->company();

		//dd($task);
		
		$this->assertTrue(true);
	}

    public function testTaskComment()
    {
        $user = User::find(1);
        
        $response = $this->actingAs($user, 'api')
                         ->get('api/tasks/1/comments');

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
                         ->post('api/tasks/1/comments', $data);

        dd($response->content());
        $response->assertStatus(200);
    }

    public function testTaskIndex()
    {
        $user = User::find(1);
        
        $response = $this->actingAs($user, 'api')
                         ->get('api/tasks');

        //dd($response->exception->validator->messages());
        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testTaskStat()
    {
        $user = User::find(1);
        
        $response = $this->actingAs($user, 'api')
                         ->get('api/tasks/statistics/3');

        //dd($response->exception->validator->messages());
        //dd($response->content());
        $response->assertStatus(200);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStore()
    {	
    	$user = User::find(1);

    	$postData = [
            'milestone_id' => 0,
	       	'title' => 'This is a title created in task test.',
	       	'description' => 'Mocking the description of task test',
	       	'started_at' => Carbon::today(),
	       	'status' => 'open',
	       	'end_at' => Carbon::today()->addDay()
        ];
        
    	//$response = $this->actingAs($user, 'api')
    	//				 ->post('api/tasks', $postData);

    	//dd($response->exception->validator->messages());

    	//$response->assertStatus(302);

    	//$this->assertTrue(true);
    }

    public function testDelete()
    {
        $user = User::find(1);
        
        //$response = $this->actingAs($user, 'api')
        //                 ->delete('api/tasks/2');

        //deleted items must not be returned
        //dd($response->content());
        $this->assertTrue(true);
    }

    public function testTask()
    {
        $user = User::find(1);

        $response = $this->actingAs($user, 'api')
                         ->get('api/tasks/2');

        //deleted items must not be returned
        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testUpdate()
    {
        $user = User::find(1);

        $postData = [
            'milestone_id' => 0,
            'title' => 'This is a title created in task update.',
            'description' => 'Mocking the description of task test',
            'started_at' => Carbon::today(),
            'status' => 'open',
            'end_at' => Carbon::today()->addDay()
        ];
        
        $response = $this->actingAs($user, 'api')
                         ->put('api/tasks/1', $postData);

        //dd($response->exception->validator->messages());

        $response->assertStatus(302);
    }
}
