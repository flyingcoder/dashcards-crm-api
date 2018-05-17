<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MltMilestone extends Model
{
    protected $fillable = ['title', 'days','milestone_template_id'];

    public function milestoneTemplate()
    {
    	return $this->belongsTo(MilestoneTemplate::class);
    }

    public function mltTasks()
    {
    	return $this->hasMany(MltTask::class);
    }

}
