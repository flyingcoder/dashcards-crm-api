<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\TeamMember;

class Team extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'description', 'slug'
    ];

    public function owner()
    {
    	return $this->belongsTo('App\User', 'owner_id', 'id');
    }

    public function members()
    {
    	return $this->belongsToMany('App\User');
    }
    
    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class, 'team_id', 'id');
    }
}
