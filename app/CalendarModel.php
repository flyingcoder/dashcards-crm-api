<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class CalendarModel extends Model
{
	use SoftDeletes, LogsActivity;

	protected $fillable = [
    	'title', 
    	'company_id',
    	'description',
    	'properties'
    ];

    protected $dates = ['deleted_at'];

    protected static $logAttributes = [
        'title', 
        'company_id',
        'description',
        'properties'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "A calendar has been {$eventName}";
    }

    public function company()
    {
    	return $this->belongsTo(Company::class);
    }

    public function events()
    {
    	return $this->hasMany(EventModel::class);
    }
}
