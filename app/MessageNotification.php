<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Musonza\Chat\Models\MessageNotification as MsgNoty;

class MessageNotification extends MsgNoty
{
    public function message()
    {
    	return $this->belongsTo(Message::class);
    }

    public function conversation()
    {
    	return $this->belongsTo(Conversation::class, 'mc_conversations', 'conversation_id');
    }

    public function markRead()
    {
    	$this->is_seen = 1;

    	$this->save();

    	return $this;
    }
}
