<?php

namespace App;

use Musonza\Chat\Models\MessageNotification as MsgNoty;

class MessageNotification extends MsgNoty
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function message()
    {
    	return $this->belongsTo(Message::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function conversation()
    {
    	return $this->belongsTo(Conversation::class, 'mc_conversations', 'conversation_id');
    }

    /**
     * @return $this
     */
    public function markRead()
    {
    	$this->is_seen = 1;
    	$this->save();
    	return $this;
    }
}
