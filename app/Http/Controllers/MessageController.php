<?php

namespace App\Http\Controllers;

use Chat;
use App\User;
use App\Events\PrivateChatSent;
use Illuminate\Http\Request;

class MessageController extends Controller
{
	public function fetchPrivateMessages($friend_id)
	{
        $friend = User::findOrFail($friend_id);

		$conversation = Chat::conversations()->between(auth()->user(), $friend);

        return Chat::conversation($conversation)
               ->for(auth()->user())
               ->getMessages();
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

    	$message = Chat::message(request()->message)
			           ->from($from)
			           ->to($conversation)
			           ->send();

        PrivateChatSent::dispatch($message, $to);

		return $message;
    }
}
