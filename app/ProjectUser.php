<?php

namespace App;

use App\Project;
use App\User;
use Illuminate\Database\Eloquent\Model;

class ProjectUser extends Model
{
	protected $table = 'project_user';

	protected $fillable = ['project_id', 'user_id', 'role'];

	public $timestamps = false;

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