<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'timer_name', 
        'description', 
        'subject_id', 
        'subject_type', 
        'causer_id', 
        'causer_type', 
        'properties'
    ];

    protected $dates = ['deleted_at'];

    public function subject()
    {
    	$this->morphTo();
    }

    public function causer()
    {
    	$this->morphTo();
    }
}
