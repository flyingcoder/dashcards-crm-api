<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectFolder extends Model
{
    protected $table = 'project_folders';

	protected $fillable = ['project_id', 'user_id', 'name', 'properties', 'source', 'folder_id', 'created_at'];
	
	protected $casts = [
		'properties' => 'array'
	];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
	{
		return $this->hasOne(User::class, 'id', 'user_id');
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
	{
		return $this->belongsTo(Project::class, 'id', 'project_id');	
	}
}
