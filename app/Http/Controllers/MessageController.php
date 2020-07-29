<?php

namespace App\Http\Controllers;

use App\Conversation;
use App\Events\ChatNotification;
use App\Events\GroupChatSent;
use App\Events\PrivateChatSent;
use App\Message;
use App\MessageNotification;
use App\Project;
use App\Repositories\MembersRepository;
use App\User;
use Chat;

class MessageController extends Controller
{
    protected $repo;
    protected $message_per_load = 10;

    /**
     *
     * @param MembersRepository $repo
     */
    public function __construct(MembersRepository $repo)
    {
        $this->repo = $repo;
        if (auth()->check()) {
            $company = auth()->user()->company();
            $this->message_per_load = $company->others['messages_page_limits'] ?? 10;
        }
    }

    /**
     * @return mixed
     */
    public function unRead()
    {
        return auth()->user()->unReadMessages();
    }

    /**
     * @return mixed
     */
    public function list()
    {
        $company = auth()->user()->company();

        $members = $company->allCompanyMembers();

        $members->map(function($user){
            if(request()->has('has_msg_count') && request()->has_msg_count) {
                $counts = 0;
                $conversation = Chat::conversations()->between(auth()->user(), $user);
                if ($conversation) {
                    $counts = $this->getUnReadNotifCounts($conversation->id);
                }
                $user->message_count = $counts;
            }
            $user->is_company_owner = $user->is_company_owner;
            return $user;
        });

        return $members;
    }

    /**
     * @return mixed
     */
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

    /**
     * @param $friend_id
     * @return mixed
     */
    public function fetchPrivateMessages($friend_id)
	{
        $friend = User::findOrFail($friend_id);

		$conversation = Chat::conversations()->between(auth()->user(), $friend);

        if(is_null($conversation))
            $conversation = Chat::createConversation([auth()->user(), $friend]);

        $messages = $conversation->messages()->latest()->paginate($this->message_per_load);
        $items = $messages->getCollection();

        $data = collect([]);
        foreach ($items as $key => $msg) {
            $msg->body = getFormattedContent($msg->body);
            $message = Message::findOrFail($msg->id);
            $data->push(array_merge($msg->toArray(), ['media' => $message->getFile() ]));   
        }

        $messages->setCollection($data);
        $this->markAllAsRead($conversation->id);

        return $messages;
	}

    public function fetchGroupMessages($convo_id) {
        $conversation = Chat::conversations()->getById($convo_id);

        $messages = $conversation->messages()->latest()->paginate($this->message_per_load);

        $items = $messages->getCollection();

        $data = collect([]);
        foreach ($items as $key => $msg) {
            $msg->body = getFormattedContent($msg->body);
            $message = Message::findOrFail($msg->id);
            $data->push(array_merge($msg->toArray(), ['media' =>  $message->getFile() ]));   
        }

        $messages->setCollection($data);
        $this->markAllAsRead($conversation->id);

        return $messages;
    }

    public function sendPrivateMessage()
    {
    	request()->validate([
    		'from_id' => 'required|exists:users,id',
            'to_id' => 'required|exists:users,id'
    	]);

        $body = request()->has('message') && !empty(request()->message) ? request()->message : ' ';
        $mention = createMentions($body);
        $body = $mention['content'];

        $from = User::findOrFail(request()->from_id);
        $to = User::findOrFail(request()->to_id);

        $conversation = Chat::conversations()->between($from, $to);
        
        if(is_null($conversation))
            $conversation = Chat::createConversation([auth()->user(), $from]);
        
    	$message = Chat::message($body)
			           ->from($from)
			           ->to($conversation)
			           ->send();

        $message->body = getFormattedContent($message->body);
        $media = null;

        if (request()->has('file') && !is_null(request()->file) ) {
            $file = request()->file('file');
            $msg = Message::findOrFail($message->id);

            $media = $msg->addMedia($file)
                            ->preservingOriginal()
                            ->withCustomProperties([
                                'ext' => $file->extension(),
                                'user' => $from
                            ])
                            ->toMediaCollection('chat.file');

            $media = $msg->getFile();
        }
        
        $data = $message->toArray() + ['sender' => $from, 'media' => $media ];

        PrivateChatSent::dispatch($data, $to);
        ChatNotification::dispatch($data, $to);

		return response()->json($data, 201);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendGroupMessage()
    {
        request()->validate([
            'from_id' => 'required|exists:users,id',
            'convo_id' => 'required|exists:mc_conversations,id'
        ]);

        $body = request()->has('message') && !empty(request()->message) ? request()->message : ' ';
        $mention = createMentions($body);
        $body = $mention['content'];

        $from = User::findOrFail(request()->from_id);
        $conversation = Chat::conversations()->getById(request()->convo_id);

        $message = Chat::message($body)
                       ->from($from)
                       ->to($conversation)
                       ->send();

        $message->body = getFormattedContent($message->body);
        $media = null;

        if (request()->has('file') && !is_null(request()->file) ) {
            $file = request()->file('file');
            $msg = Message::findOrFail($message->id);

            $media = $msg->addMedia($file)
                            ->preservingOriginal()
                            ->withCustomProperties([
                                'ext' => $file->extension(),
                                'user' => $from
                            ])
                            ->toMediaCollection('chat.file');

            $media = $msg->getFile();
        }

        $data = $message->toArray() + ['sender' => $from, 'media' => $media ];

        GroupChatSent::dispatch($data, $conversation);

        return response()->json($data, 201);
    }

    /**
     * @param null $conversation_id
     * @return int
     */
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

    /**
     * @param null $conversation_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllAsRead($conversation_id = null)
    {
        $notifQuery =  MessageNotification::where('user_id', '=', auth()->user()->id)
                        ->where('is_sender', '=', 0);

        if ($conversation_id > 0 && !is_null($conversation_id)) {
            $notifQuery = $notifQuery->where('conversation_id', '=', $conversation_id);
        }
        $notifQuery->update(['is_seen' => 1]);

        return response()->json(['message' => 'Success'], 200);
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

    /**
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * @param $type
     * @param $project_id
     * @return mixed
     */
    public function getGroupInfo($type, $project_id)
    {   
        if (!in_array($type, ['client', 'team'])) {
            abort(500, 'Invalid group type!');
        }

        $project = Project::findOrFail($project_id);
        $project_company = $project->company;
        
        $conversation = $project->conversations()->where('type', $type)->first();
        $participants1 = $conversation->users()->get()->toArray();

        if (!$conversation || empty($participants1)) {
            if ($type == 'client') {
                $members = $this->repo->getProjectClientChatMembers($project);
                $participants = array_unique($members->pluck('id')->toArray());
            } else {
                $members = $this->repo->getProjectTeamChatMembers($project);
                $participants = $members->pluck('id')->toArray() ?? [];
            }
        }

        if (!$conversation) {
            $data = array(
                'type' => $type,
                'group_name' => $project_company->name." ".ucwords($type)." Message Group",
                'company_id' => $project_company->id,
            );
            
            $conversation = Chat::createConversation($participants, $data);
            $conversation->type = $type;
            $conversation->project_id = $project_id;
            $conversation->save();
        }

        if (empty($participants1)) {
            $conversation->addParticipants($participants);
        }
        $members = $conversation->users()->get();
        foreach ($members as $key => $member) {
            $members[$key]->is_company_owner = $member->is_company_owner;
        }
        $conversation->members = $members->toArray();

        return $conversation;
    }


}
    