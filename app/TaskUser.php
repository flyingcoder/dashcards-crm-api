<?php

namespace App;

use App\Task;
use App\User;
use Illuminate\Database\Eloquent\Model;

class TaskUser extends Model
{
	protected $table = 'project_user';

	public function users()
	{
		return $this->hasMany(User::class, 'id', 'user_id');
	}

	public function task()
	{
		return $this->belongsTo(Task::class, 'id', 'task_id');	
	}
}