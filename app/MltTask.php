<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MltTask extends Model
{

    protected $fillable = ['title', 'days', 'description','mlt_milestone_id'];

    public function mltMilestone()
    {
    	return $this->belongTo(MltMilestone::class);
    }
}
