<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MilestoneTemplate extends Model
{
    protected $fillable = [
        'title', 'is_active','user_id'
    ];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function mltMilestones()
    {
    	return $this->hasMany(MltMilestone::class);
    }
}
