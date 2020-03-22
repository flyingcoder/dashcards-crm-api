<?php

namespace App\Http\Controllers;

use App\Conversation;
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

    public function groupList()
    {
        $user_id = auth()->user()->id;
        $conversations = Conversation::join('mc_conversation_user', function ($join) use ($user_id){
                $join->on('mc_conversation_user.conversation_id', '=', 'mc_conversations.id')
                    ->where('mc_conversation_user.user_id', '=', $user_id);
            })->where('mc_conversations.type', '=', 'group')
            ->get();

        $conversations->map(function($conversation){
            if(request()->has('has_msg_count') && request()->has_msg_count) {
                $counts = $this->getUnReadNotifCounts($conversation->id);
                $conversation->message_count = $counts;
            }
            $conversation->is_online = 1;
            $conversation->fullname = $conversation->data['group_name'];
            $conversation->members = $conversation->users()->get();
            return $conversation;
        });
        return $conversations;
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

    public function fetchGroupMessages($convo_id) {
        $conversation = Chat::conversations()->getById($convo_id);

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
    
    public function sendGroupMessage()
    {
        request()->validate([
            'message' => 'required|string',
            'from_id' => 'required|exists:users,id',
            'convo_id' => 'required|exists:mc_conversations,id'
        ]);
        $from = User::findOrFail(request()->from_id);
        $conversation = Chat::conversations()->getById(request()->convo_id);

        $message = Chat::message(request()->message)
                       ->from($from)
                       ->to($conversation)
                       ->send();

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

    public function createGroupChat()
    {
        request()->validate([
            'users' => 'required|array',
            'group_name' => 'required|string|min:5'
        ]);
        $logged_user = auth()->user();
        $participants = User::whereIn('id', request()->users)->orWhere('id', $logged_user->id)->get();
        $participants = $participants->pluck('id')->toArray();

        $data = array(
                'type' => 'group',
                'group_name' => request()->group_name,
                'company_id' => $logged_user->company()->id,
                'group_creator' => $logged_user
            );

        $conversation = Chat::createConversation($participants,$data);
        $conversation->type = 'group';
        $conversation->save();

        $conversation->is_online = 1;
        $conversation->fullname = request()->group_name;
        $conversation->members = $conversation->users()->get();

        return response()->json($conversation->toArray(), 201);
    }

    public function removeFromGroup()
    {
        request()->validate([
            'user_id' => 'required|exists:users,id',
            'convo_id' => 'required|exists:mc_conversations,id'
        ]);

        $conversation = Chat::conversations()->getById(request()->convo_id);
        $conversation->removeUsers([request()->user_id]);
        $conversation->is_online = 1;
        $conversation->fullname = $conversation->data['group_name'];
        $conversation->members = $conversation->users()->get();

        return response()->json($conversation->toArray(), 200);
    }

    public function groupChatMembers($conversation_id)
    {
        $conversation = Chat::conversations()->getById($conversation_id);

        if (!$conversation) {
            return response()->json(['message' => 'Conversation not found!'], 522);    
        }

        return response()->json($conversation->users()->get()->toArray(), 200);
    }

    public function updateGroupChatMembers()
    {
        request()->validate([
            'convo_id' => 'required|exists:mc_conversations,id',
            'users' => 'required|array'
        ]);
        
        $conversation = Chat::conversations()->getById(request()->convo_id);
        
        $convo_users = $conversation->users()->pluck('id')->toArray();

        $conversation->removeUsers($convo_users);

        $conversation->addParticipants(request()->users);
        
        return response()->json($conversation->users()->get()->toArray(), 200);
    }
}
    