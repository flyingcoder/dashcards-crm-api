<?php


namespace App\Traits;


use App\Conversation;
use App\Message;
use App\MessageNotification;
use App\User;
use Chat;

trait ConversableTrait
{

    /**
     * @param null $target
     * @return mixed
     * @throws \Exception
     */
    public function privateRoom($target = null)
    {
        if (is_null($target))
            throw new \Exception('Conversation partner not found!');

        if (is_numeric($target)) {
            $target = User::find($target);
        }
        $self = $this;
        $conversation = Chat::conversations()->between($self, $target);
        if (!$conversation)
            $conversation = Chat::createConversation([$target, $self], ['group_name' => $self->fullname.' & '.$target->fullname ])->makePrivate();

        return $conversation;
    }

    /**
     * @return Conversation
     */
    public function teamProjectRoom()
    {
        $conversation = $this->conversations()->where('type', 'team')->first();

        if (!$conversation) {
            $company = $this->company;
            $client = $this->client()->first();
            $members = $this->team()->where('id', '<>', $client->id)->get();
            $conversation = Chat::createConversation([$members, $client])->makePrivate();
            $conversation->data = ['group_name' => $company->name . " Team Message Group", 'group_creator' => 'system', 'company' => $company->id];
            $conversation->type = 'team';
            $conversation->project_id = $this->id;
            $conversation->save();
        }
        return $conversation;
    }

    /**
     * @return Conversation
     */
    public function clientProjectRoom()
    {
        $conversation = $this->conversations()->where('type', 'client')->first();

        if (!$conversation) {
            $company = $this->company;
            $client = $this->client;
            $managers = $this->manager;
            $conversation = Chat::createConversation([$managers, $client])->makePrivate(false);
            $conversation->data = ['group_name' => $company->name . " Client Message Group", 'group_creator' => 'system', 'company' => $company->id];
            $conversation->type = 'client';
            $conversation->project_id = $this->id;
            $conversation->save();
        }
        return $conversation;
    }

    /**
     * @param $id
     * @return Conversation
     */
    public function groupChat($id = null, $props = [])
    {
        $conversation = Chat::conversations()->getById($id);
        if (!$conversation && request()->user()) {
            $conversation = Chat::createConversation([request()->user()])->makePrivate(false);
            $data = ['title' => 'Private Group', 'group_creator' => request()->user()->id ?? 'system'] + $props;
            $conversation->update(['data' => $data]);
        }
        return $conversation;
    }

    /**
     * Creates an entry in the message_notification table for each participant
     * This will be used to determine if a message is read or deleted.
     * @param Message $message
     */
    public function createNotifications($message)
    {
        if ($message->conversation) {
            $notifications = [];

            foreach ($message->conversation->users as $user) {
                $is_sender = ($message->user_id == $user->getKey()) ? 1 : 0;

                $notifications[] = [
                    'user_id' => $user->getKey(),
                    'message_id' => $message->id,
                    'conversation_id' => $message->conversation->id,
                    'is_seen' => $is_sender,
                    'is_sender' => $is_sender,
                    'created_at' => $message->created_at
                ];
            }

            MessageNotification::insert($notifications);
        }
    }
}