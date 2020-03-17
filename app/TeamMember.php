<?php

namespace App;

use App\Team;
use App\User;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
	protected $table = 'team_user';

	public function user()
	{
		return $this->hasOne(User::class, 'id', 'user_id');
	}

	public function team()
	{
		return $this->belongsTo(Team::class, 'id', 'team_id');	
	}
}