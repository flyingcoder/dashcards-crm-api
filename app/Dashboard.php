<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Dashboard extends Model
{
	use SoftDeletes, LogsActivity;

	protected $fillable = ['title', 'email', 'description'];

	protected static $logAttributes = ['title', 'email', 'description'];

	public function getDescriptionForEvent(string $eventName): string
    {
        return "A Dashboard has been {$eventName}";
    }

    public function dashitems()
    {
    	return $this->belongsToMany(Dashitem::class)->withPivot('order', 'visible');
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
