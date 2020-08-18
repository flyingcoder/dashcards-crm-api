<?php

namespace App;

use Musonza\Chat\Models\Conversation as Convo;

/**
 * Class Conversation
 * @package App
 */
class Conversation extends Convo
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notifications()
    {
        return $this->hasMany(MessageNotification::class, 'conversation_id');
    }

    /**
     * Return the recent message in a Conversation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function last_message()
    {
        return $this->hasOne(Message::class)->orderBy('mc_messages.id', 'desc')->with('sender');
    }

    /**
     * Messages in conversation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(Message::class, 'conversation_id')->with('sender');
    }
}
