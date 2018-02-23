<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Template extends Model
{
	use SoftDeletes, LogsActivity;

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id', 'status', 'name', 'replica_type'
    ];

    protected static $logAttributes = [
        'company_id', 'status', 'name', 'replica_type'
    ];

    protected $dates = ['deleted_at'];

    public function paginatedMilestoneTemplate(Request $request)
    {
        
    }
    
    public function company()
    {
    	return $this->belongsTo(Company::class);
    }
}
