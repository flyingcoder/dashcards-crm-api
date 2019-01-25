<?php

namespace App\Http\Controllers;

use Chat;
use App\User;
use App\Message;
use App\MessageNotification;
use App\Events\PrivateChatSent;
use App\Events\ChatNotification;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function unRead()
    {
        return auth()->user()->unReadMessages();
    }

	public function fetchPrivateMessages($friend_id)
	{
        $friend = User::findOrFail($friend_id);

		$conversation = Chat::conversations()->between(auth()->user(), $friend);

        if(is_null($conversation))
            $conversation = Chat::createConversation([auth()->user(), $friend]);

        return $conversation->messages()
                            ->paginate(10);
	}

    public function sendPrivateMessage()
    {
    	request()->validate([
    		'message' => 'required|string',
    		'from_id' => 'required|exists:users,id',
            'to_id' => 'required|exists:users,id'
    	]);

        $from = User::findOrFail(request()->from_id);

        $to = User::findOrFail(request()->to_id);

        $conversation = Chat::conversations()->between($from, $to);
        
        if(is_null($conversation))
            $conversation = Chat::createConversation([auth()->user(), $from]);
        
    	$message = Chat::message(request()->message)
			           ->from($from)
			           ->to($conversation)
			           ->send();

        PrivateChatSent::dispatch($message, $to);

        ChatNotification::dispatch($message, $to);

		return $message;
    }
}
