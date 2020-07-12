<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
	protected $table = 'team_user';
	public $timestamps = false;
	public $fillable = [ 'team_id', 'user_id'];

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
    public function team()
	{
		return $this->belongsTo(Team::class, 'id', 'team_id');	
	}
}