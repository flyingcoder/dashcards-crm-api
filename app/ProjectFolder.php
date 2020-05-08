<?php

namespace App;

use App\Project;
use App\User;
use Illuminate\Database\Eloquent\Model;

class ProjectFolder extends Model
{
    protected $table = 'project_folders';

	protected $fillable = ['project_id', 'user_id', 'name', 'properties', 'source', 'folder_id', 'created_at'];
	
	protected $casts = [
		'properties' => 'array'
	];

	public function user()
	{
		return $this->hasOne(User::class, 'id', 'user_id');
	}

	public function project()
	{
		return $this->belongsTo(Project::class, 'id', 'project_id');	
	}
}
