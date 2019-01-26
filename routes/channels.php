<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/


Broadcast::channel('auto-logout', function ($user, $id) {
    return true;
});

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.typing-{companyId}', function ($user, $companyId) {
	if((int) $user->company()->id === (int) $companyId){
		return $user;
	}
});

Broadcast::channel('chat.new-message.{toId}', function ($user, $toId) {
	if((int) $user->id === (int) $toId){
		return $user;
	}
});

Broadcast::channel('chat.notification.{toId}', function ($user, $toId) {
	if((int) $user->id === (int) $toId){
		return $user;
	}
});

Broadcast::channel('friend-list-{companyId}', function ($user, $companyId) {
	if((int) $user->company()->id === (int) $companyId){
		return $user;
	}
});

Broadcast::channel('comment.task.{taskId}', function ($comment, $taskId) {
	if((int) $comment->commentable->id === (int) $taskId){
		return $comment;
	}
});


Broadcast::channel('project.notification.{companyId}', function ($user, $companyId) {
	if((int) $user->company()->id === (int) $companyId){
		return $user;
	}
});
