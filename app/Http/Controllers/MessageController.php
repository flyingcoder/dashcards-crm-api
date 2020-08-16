<?php

namespace App\Http\Controllers;

use App\Conversation;
use App\Events\ChatMessageSent;
use App\Message;
use App\MessageNotification;
use App\Project;
use App\Repositories\MembersRepository;
use App\Traits\ConversableTrait;
use App\User;
use Chat;

/**
 * Class MessageController
 * @package App\Http\Controllers
 */
class MessageController extends Controller
{
    use ConversableTrait;
    /**
     * @var MembersRepository
     */
    protected $repo;
    /**
     * @var int
     */
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

        $members->map(function ($user) {
            if (request()->has('has_msg_count') && request()->has_msg_count) {
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
        $conversations = Conversation::join('mc_conversation_user', function ($join) use ($user_id) {
            $join->on('mc_conversation_user.conversation_id', '=', 'mc_conversations.id')
                ->where('mc_conversation_user.user_id', '=', $user_id);
        })->where('mc_conversations.type', '=', 'group')
            ->get();

        $conversations->map(function ($conversation) {
            if (request()->has('has_msg_count') && request()->has_msg_count) {
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

        if (is_null($conversation))
            $conversation = Chat::createConversation([auth()->user(), $friend]);

        $messages = $conversation->messages()->latest()->paginate($this->message_per_load);
        $items = $messages->getCollection();

        $data = collect([]);
        foreach ($items as $key => $msg) {
            $msg->body = getFormattedContent($msg->body);
            $message = Message::findOrFail($msg->id);
            $data->push(array_merge($msg->toArray(), ['media' => $message->getFile()]));
        }

        $messages->setCollection($data);
        $this->markAllAsRead($conversation->id);

        return $messages;
    }

    /**
     * @param $convo_id
     * @return mixed
     */
    public function fetchGroupMessages($convo_id)
    {
        $conversation = Chat::conversations()->getById($convo_id);

        $messages = $conversation->messages()->latest()->paginate($this->message_per_load);

        $items = $messages->getCollection();

        $data = collect([]);
        foreach ($items as $key => $msg) {
            $msg->body = getFormattedContent($msg->body);
            $message = Message::findOrFail($msg->id);
            $data->push(array_merge($msg->toArray(), ['media' => $message->getFile()]));
        }

        $messages->setCollection($data);
        $this->markAllAsRead($conversation->id);

        return $messages;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
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

        $conversation = $from->privateRoom($to);

        $message = $conversation->messages()
            ->create([
                'body' => $body,
                'user_id' => $from->id,
                'type' => 'text',
                'created_at' => now()
            ]);

        $message->body = getFormattedContent($message->body);
        $media = null;

        if (request()->has('file') && !is_null(request()->file)) {
            $file = request()->file('file');
            $message->addMedia($file)
                ->preservingOriginal()
                ->withCustomProperties([
                    'ext' => $file->extension(),
                    'user' => $from
                ])
                ->toMediaCollection('chat.file');

            $media = $message->getFile();
        }

        $data = $message->toArray() + ['sender' => $from, 'media' => $media];

        broadcast(new ChatMessageSent($message));
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
        $conversation = $this->groupChat(request()->convo_id);

        $message = Chat::message($body)->from($from)->to($conversation)->send();

        $message->body = getFormattedContent($message->body);
        $media = null;

        if (request()->has('file') && !is_null(request()->file)) {
            $file = request()->file('file');
            $msg = Message::findOrFail($message->id);

            $media = $msg->addMedia($file)
                ->preservingOriginal()
                ->withCustomProperties([
                    'ext' => $file->extension(),
                    'user' => $from->id
                ])
                ->toMediaCollection('chat.file');

            $media = $msg->getFile();
        }

        $data = $message->toArray() + ['sender' => $from, 'media' => $media];

        //remove redundant broadcast
        //GroupChatSent::dispatch($data, $conversation);

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
     * @param $conversation_id
     * @return mixed
     */
    protected function readConversation($conversation_id)
    {
        $notificationQuery = MessageNotification::where('user_id', '=', auth()->user()->id)
            ->where('is_sender', '=', 0);

        if ($conversation_id > 0 && !is_null($conversation_id)) {
            $notificationQuery = $notificationQuery->where('conversation_id', '=', $conversation_id);
        }

        return $notificationQuery->update(['is_seen' => 1]);
    }

    /**
     * @param null $conversation_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllAsRead($conversation_id = null)
    {
        $this->readConversation($conversation_id);

        return response()->json(['message' => 'Success'], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
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
            'group_name' => request()->group_name,
            'company' => $logged_user->company()->id,
            'group_creator' => $logged_user->id
        );

        $conversation = Chat::createConversation($participants, $data);
        $conversation->type = 'group';
        $conversation->save();

        $conversation->fullname = request()->group_name;
        $conversation->members = $conversation->users;

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

    /**
     * @param $conversation_id
     * @return \Illuminate\Http\JsonResponse
     */
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

        $conversation = $type == 'client' ? $project->clientProjectRoom() : $project->teamProjectRoom();

        if (!$conversation) {
            abort(500, ucwords($type) . ' Conversation unavailable');
        }

        $conversation->members = $conversation->users;

        return $conversation;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function conversationList()
    {
        $user = auth()->user();
        $company = $user->company();
        $members = $company->company_members()->where('id', '<>', $user->id)->get();
        $user_list = collect([]);
        foreach ($members as $member) {
            $conversation = $user->privateRoom($member);
            $conversation->user = $member->basics();
            $member->conversation = $conversation;
            if (request()->has('has_msg_count') && request()->has_msg_count) {
                $member->message_count = $this->getUnReadNotifCounts($member->conversation->id);
            }
            $user_list->push($member);
        }

        $group_list = collect([]);
        $groups = $user->conversations()->with('users')->where('type', 'group')->get();
        foreach ($groups as $group) {
            if (request()->has('has_msg_count') && request()->has_msg_count) {
                $group->message_count = $this->getUnReadNotifCounts($group->id);
            }
            $group_list->push($group);
        }

        return response()->json(['user_list' => $user_list, 'group_list' => $group_list], 200);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function fetchConversationMessages($id)
    {
        $conversation = auth()->user()->conversations()->where('id', $id)->first();

        if (!$conversation)
            abort(404, 'No conversation found!');

        $messages = $conversation->messages()
            ->latest()
            ->paginate($this->message_per_load);

        $items = $messages->getCollection();
        $data = collect([]);
        foreach ($items as $key => $message) {
            $message->body = getFormattedContent($message->body);
            $data->push(array_merge($message->toArray(), ['media' => $message->getFile()]));
        }
        $messages->setCollection($data);

        $this->markAllAsRead($conversation->id);

        return $messages;
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendConversationMessage($id)
    {
        request()->validate(['conversation_id' => 'required|exists:mc_conversations,id']);
        $body = ' ';
        if (request()->has('message') && !empty(request()->message)) {
            $mention = createMentions(request()->message);
            $body = $mention['content'];
        }

        $from = request()->user();
        $conversation = $from->conversations()->where('id', request()->id)->firstOrFail();
        $message = Chat::message($body)->from($from)->to($conversation)->send();
        $message->body = getFormattedContent($message->body);
        $media = null;

        if (request()->has('file') && !is_null(request()->file)) {
            $file = request()->file('file');
            $message = Message::findOrFail($message->id);
            $message->addMedia($file)->preservingOriginal()
                ->withCustomProperties(['ext' => $file->extension(), 'user' => $from->id])
                ->toMediaCollection('chat.file');

            $media = $message->getFile();
        }
        $message->body = getFormattedContent($message->body);

        $data = $message->toArray() + ['sender' => $from, 'media' => $media];

        broadcast(new ChatMessageSent($message));

        return response()->json($data, 201);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function manageConversationMembers($id)
    {
        request()->validate([
            'conversation_id' => 'required|exists:mc_conversations,id',
            'users' => 'required|array'
        ]);
        $user = auth()->user();
        $conversation = $user->conversations()->where('id', request()->conversation_id)->firstOrFail();
        $new_users = User::whereIn('id', request()->users)->get();

        if ($new_users->isEmpty() || $new_users->count() < 2)
            abort(500, 'Conversation must have atleast 2 members!');

        Chat::conversation($conversation)->removeParticipants($conversation->users);

        $conversation->addParticipants([$new_users]);

        $conversation->load('users');

        return $conversation;
    }

    /**
     * @return mixed
     */
    public function newGroupConversation()
    {
        request()->validate([
            'users' => 'required|array',
            'group_name' => 'required|string|min:5'
        ]);
        $logged_user = auth()->user();
        $participants = User::whereIn('id', request()->users)->orWhere('id', $logged_user->id)->get();

        $data = array(
            'group_name' => request()->group_name,
            'company' => $logged_user->company()->id,
            'group_creator' => $logged_user->id
        );

        $conversation = Chat::createConversation([$participants], $data);
        $conversation->type = 'group';
        $conversation->save();

        $conversation->load('users');

        return $conversation;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function conversationByUser($id)
    {
        $friend = User::findOrFail($id);

        $conversation = request()->user()->privateRoom($friend);
        $messages = $conversation->messages()->with('sender')->latest()->paginate($this->message_per_load);
        $items = $messages->getCollection();
        $data = collect([]);
        foreach ($items as $key => $message) {
            $message->body = getFormattedContent($message->body);
            $message->setRelation('conversation', $conversation);
            $data->push(array_merge($message->toArray(), ['media' => $message->getFile()]));
        }
        $messages->setCollection($data);

        $this->markAllAsRead($conversation->id);

        return $messages;
    }
}
    