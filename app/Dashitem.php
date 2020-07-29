<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dashitem extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function dashboards()
    {
        return $this->belongsToMany(Dashboard::class)->withPivot('order');
    }
}
