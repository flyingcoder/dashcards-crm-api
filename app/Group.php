<?php

namespace App;

use Kodeine\Acl\Models\Eloquent\Role;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Role
{
	use SoftDeletes,
		Sluggable;

   	public function company()
   	{
   		return $this->belongsTo(Company::class);
   	}
}
