<?php

namespace App;

use App\EventModel;
use App\User;
use Illuminate\Database\Eloquent\Model;

class EventParticipant extends Model
{
    protected $table = 'event_participants';

    protected $fillable = [
        'event_id', 
        'user_id',
        'added_by'
    ];

    public function event()
    {
    	return $this->belongsTo(EventModel::class, 'event_id', 'id');
    }

    public function user()
    {
    	return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function addedBy()
    {
    	return $this->hasOne(User::class, 'id', 'added_by');
    }

}
