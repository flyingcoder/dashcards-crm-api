<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Musonza\Chat\Models\Message as Msg;

class Message extends Msg
{
    public function sender()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }
}
