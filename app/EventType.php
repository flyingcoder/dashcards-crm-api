<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    protected $table = 'event_types';

    protected $primaryKey = 'id';

    protected $fillable = [
        'created_by',
        'calendar_id',
        'properties',
        'is_public',
        'name'
    ];

    protected $casts = [
        'properties' => 'array'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->hasMany(EventModel::class, 'event_type', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function creator()
    {
        return $this->hasOne(User::class, 'user_id', 'id');
    }
}
