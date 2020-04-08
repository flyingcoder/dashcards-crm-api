<?php

namespace App;

use Spatie\Activitylog\Models\Activity as Act;

class Activity extends Act
{
	protected $cast = [
		'read' => 'boolean'
	];

    public function users()
    {
    	return $this->belongsToMany(User::class, 'activity_user', 'activity_id', 'user_id')
    				->withPivot('read_at');
    }
}
