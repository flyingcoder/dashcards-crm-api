<?php

namespace Tests\Feature;

use App\User;
use App\Note;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NoteTest extends TestCase
{
    public function testIndex()
    {
        $this->withoutExceptionHandling();

        $user = User::find(1);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->get("api/note");

        dd($response->content());
        $response->assertStatus(200);
    }

    public function testPinning()
    {
        $this->withoutExceptionHandling();

        $user = User::find(1);

        $model = Note::latest()->first();

        //$this->assertTrue(true);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->put("api/note/".$model->id."/pin");

        //dd($response->content());
        $response->assertStatus(200);

    }

    public function testStore()
    {
        $this->withoutExceptionHandling();

        $user = User::find(1);

        $data = [
            'title' => 'Take note 1',
            'content' => 'this is a content'
        ];

        $this->assertTrue(true);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->post("api/note", $data);

        //dd($response->content());
        $response->assertStatus(200);

    }

    public function testAssign()
    {
        $this->withoutExceptionHandling();

        $user = User::find(1);

        $model = Note::latest()->first();

        $data = [
        	'users_id' => [2,3]
        ];

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->post("api/note/".$model->id."/collaborators", $data);

        //dd($response->content());
        $response->assertStatus(200);
    }

    public function testUpdate()
    {
        $this->withoutExceptionHandling();

        $user = User::find(1);

        $model = Note::latest()->first();

        $data = [
            'title' => 'Take note',
            'content' => 'this content has been updated'
        ];

        //$this->assertTrue(true);

        $response = $this->actingAs($user, 'api')
                         ->withHeaders(['HTTP_X-Requested-With' => 'XMLHttpRequest'])
                         ->put("api/note/".$model->id, $data);

        //dd($response->content());
        $response->assertStatus(200);

    }
}
