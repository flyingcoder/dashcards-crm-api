<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	protected $fillable = ['body', 'causer_id', 'causer_type'];

    public function commentable()
    {
    	return $this->morphTo();
    }

    public function causer()
    {
    	return $this->morphTo();
    }
}
