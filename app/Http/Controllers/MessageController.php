<?php

namespace App\Http\Controllers;

use Chat;
use App\User;
use App\Events\ChatSent;
use Illuminate\Http\Request;

class MessageController extends Controller
{
	public function fetchPrivateMessages($user1_id, $user2_id)
	{
        $user1 = User::findOrFail($user1_id);

        $user2 = User::findOrFail($user2_id);

		$conversation = Chat::conversations()->between($user1, $user2);

        return Chat::conversation($conversation)
               ->for($user1)
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

        ChatSent::dispatch($message);

		return $message;
    }
}
