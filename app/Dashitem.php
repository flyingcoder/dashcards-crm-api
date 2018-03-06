<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dashitem extends Model
{
    public function dashboards()
    {
    	return $this->belongsToMany(Dashboard::class)->withPivot('order');
    }
}
