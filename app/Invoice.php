<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'date',
        'user_id',
        'discount',
        'title', 
        'project_id',
        'due_date',
        'items',
        'total_amount',
        'terms',
        'tax',
        'due_date',
        'company_logo'
    ];

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
    	return $this->belongsTo(Project::class);
    }
}
