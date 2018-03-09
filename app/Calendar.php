<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Calendar extends Model
{
	use SoftDeletes;

	protected $fillable = [
    	'title', 
    	'company_id',
    	'description',
    	'properties'
    ];

    protected $dates = ['deleted_at'];

    public function company()
    {
    	return $this->belongsTo(Company::class);
    }

    public function events()
    {
    	return $this->hasMany(Event::class);
    }
}
