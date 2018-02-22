<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'description', 'slug'
    ];

    public function owner()
    {
    	return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function members()
    {
    	return $this->belongsToMany(User::class);
    }
    
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
