<?php

namespace App\Http\Controllers;

use App\Events\ChatNotification;
use App\Events\PrivateChatSent;
use App\Message;
use App\MessageNotification;
use App\User;
use Chat;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function unRead()
    {
        return auth()->user()->unReadMessages();
    }

    public function list()
    {
        $company = auth()->user()->company();

        $members = $company->allCompanyMembers();

        if(request()->has('has_msg_count') && request()->has_msg_count) {
            $members->map(function($user){
                $counts = 0;
                $conversation = Chat::conversations()->between(auth()->user(), $user);
                if ($conversation) {
                    $counts = $this->getUnReadNotifCounts($conversation->id);
                }
                $user->message_count = $counts;
                return $user;
            });
        }

        return $members;
    }

	public function fetchPrivateMessages($friend_id)
	{
        $friend = User::findOrFail($friend_id);

		$conversation = Chat::conversations()->between(auth()->user(), $friend);

        if(is_null($conversation))
            $conversation = Chat::createConversation([auth()->user(), $friend]);

        $messages = $conversation->messages()->latest()->paginate(10);

        $this->markAllAsRead($conversation->id);

        return $messages;
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

		return response()->json( $message->toArray() + ['sender' => $from], 201);
    }

    public function getUnReadNotifCounts($conversation_id = null)
    {
        if ($conversation_id == 0 || is_null($conversation_id)) {
            return 0;
        }
        return MessageNotification::where('user_id', '=', auth()->user()->id)
                        ->where('is_sender', '=', 0)
                        ->where('conversation_id', '=', $conversation_id)
                        ->where('is_seen', '=', 0)
                        ->count();
    }

    public function markAllAsRead($conversation_id = null)
    {
        if ($conversation_id > 0 && !is_null($conversation_id)) {
            MessageNotification::where('user_id', '=', auth()->user()->id)
                        ->where('is_sender', '=', 0)
                        ->where('conversation_id', '=', $conversation_id)
                        ->update(['is_seen' => 1]);
        }
    }
}
