<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Backlog extends Model
{
    protected $table = 'backlogs';
  
    protected $primaryKey = 'id';

    protected $fillable = [
    	'event_id',
    	'account',
    	'data',
    	'event_type',
    	'livemode'
    ];

    protected $casts = [
    	'data' => 'object',
    	'livemode' => 'boolean'
    ];
}
