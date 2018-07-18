<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
	protected $fillable = ['title', 'email', 'description'];

    public function dashitems()
    {
    	return $this->belongsToMany(Dashitem::class)->withPivot('order');
    }

 	public function company()
 	{
 		return $this->belongsTo(Company::class);
 	}

 	public function itemByOrder($order)
 	{
 		return $this->belongsToMany(Dashitem::class)->wherePivot('order', 1);
 	}
}
