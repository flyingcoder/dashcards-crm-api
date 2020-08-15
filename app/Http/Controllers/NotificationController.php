<?php

namespace App\Http\Controllers;

use App\User;
use Chat;

class NotificationController extends Controller
{

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index()
    {
        $user = request()->user();
        if (request()->has('type') && request()->type == 'chat-notification')
            return $this->getChatNotification($user);

        return $this->getOtherNotifications($user);
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getChatNotification(User $user)
    {
        $conversations = $user->conversations()
            ->whereHas('last_message')
            ->with('last_message')
            ->paginate(request()->per_page ?? 15);

        $items = $conversations->getCollection();
        $data = collect([]);
        foreach ($items as $key => $chat) {
            $message = $chat->last_message;
            $message->body = getFormattedContent($message->body);
            unset($chat['last_message']);
            $message->setRelation('conversation', $chat);
            $message->setRelation('notification', $message->getNotification($user));

            if (!$chat->type) {
                $message->setRelation('user', $chat->users()->where('id', '<>', $user->id)->first());
            } else {
                $message->setRelation('user', null);
            }
            $data->push($message);
        }
        $conversations->setCollection($data);

        return $conversations;
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getOtherNotifications(User $user)
    {
        return $user->notifications()->where('type', 'App\\Notifications\\CompanyNotification')
            ->orderBy('read_at', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(request()->per_page ?? 15);
    }

    /**
     * @return mixed
     */
    public function unread()
    {
        return auth()->user()->unreadActivity();
    }

    /**
     * @return int
     */
    public function unreadcount()
    {
        return count(auth()->user()->unreadActivity());
    }


    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function notificationMarkAsRead($id)
    {
        request()->user()
            ->unreadNotifications()
            ->where('id', $id)
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'Success'], 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function chatMarkAsRead($id)
    {
        $conversation = Chat::conversations()->getById($id);
        Chat::conversation($conversation)->setParticipant(request()->user())->readAll();

        return response()->json(['message' => 'Success'], 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllChatAsRead()
    {
        $user = request()->user();
        $conversations = $user->conversations();

        foreach ($conversations as $conversation) {
            Chat::conversation($conversation)->setParticipant($user)->readAll();
        }

        return response()->json(['message' => 'Success'], 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllCompanyAsRead()
    {
        request()->user()
            ->unreadNotifications()
            ->where('type', 'App\\Notifications\\CompanyNotification')
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'Success'], 200);
    }
}
