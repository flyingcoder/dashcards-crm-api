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
        $conversation = $this->getCommonPrivateConversation($self, $target);
        if ($conversation !== false)
            return $self->conversations()->whereNull('type')->where('id', $conversation)->first();
        if (!$conversation)
            $conversation = Chat::createConversation([$target, $self], ['group_name' => $self->fullname.' & '.$target->fullname ])->makePrivate();

        return $self->conversations()->where('id', $conversation->id)->first();
    }

    /**
     * @param $user1
     * @param $user2
     * @return bool|mixed
     */
    public function getCommonPrivateConversation($user1, $user2)
    {
        $conversation1 = collect($user1->conversations()->whereNull('type')->pluck('id')->toArray());
        $conversation2 = collect($user2->conversations()->whereNull('type')->pluck('id')->toArray());
        $intersect = $conversation1->intersect($conversation2);
        return $intersect->isEmpty() ? false : $intersect->first();
    }
    /**
     * @return Conversation
     */
    public function teamProjectRoom()
    {
        $conversation = $this->conversations()->where('type', 'team')->first();

        if (!$conversation) {
            $company = $this->company;
            $clients = $this->client()->pluck('id')->toArray();
            $members = $this->team()->whereNotIn('id', $clients)->get();
            $conversation = Chat::createConversation([$members])->makePrivate();
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
    public function updateTeamProjectRoomUsers()
    {
        $project = $this;
        $conversation = $project->teamProjectRoom();
        $conversation->users()->detach();

        $clients = $project->client()->pluck('id')->toArray();
        $members = $project->team()->whereNotIn('id', $clients)->get();
        foreach ($members as $member) {
            $conversation->addParticipants($member->id);
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
     * @return Conversation
     */
    public function updateClientProjectRoomUsers()
    {
        $project = $this;
        $conversation = $project->clientProjectRoom();
        $conversation->users()->detach();

        $client = $project->client;
        $managers = $project->manager;
        foreach ($managers as $member) {
            $conversation->addParticipants($member->id);
        }
        foreach ($client as $member) {
            $conversation->addParticipants($member->id);
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