<?php

namespace App;

use App\Project;
use App\User;
use Illuminate\Database\Eloquent\Model;

class ProjectUser extends Model
{
	protected $table = 'project_user';

	public function user()
	{
		return $this->hasOne(User::class, 'id', 'user_id');
	}

	public function project()
	{
		return $this->belongsTo(Project::class, 'id', 'project_id');	
	}
}