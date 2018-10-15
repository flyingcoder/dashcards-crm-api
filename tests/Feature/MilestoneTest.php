<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Milestone;
use App\User;
use App\Company;

class MilestoneTest extends TestCase
{

    public function testMilestone()
    {
    	$this->withoutExceptionHandling();

        $user = User::find(1);

        $model = Milestone::latest()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get('api/milestone/'.$model->id);

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testDelete()
    {
       $this->withoutExceptionHandling();

        $user = User::findOrFail(1);

        $model = Milestone::latest()->first();

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->delete('api/milestone/'.$model->id);

        //dd($response->content());
        $response->assertStatus(200);
    }
}
