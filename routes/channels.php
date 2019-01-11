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

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.new-message.{companyId}', function ($user, $companyId) {
	if((int) $user->company()->id === (int) $companyId){
		return $user;
	}
});

Broadcast::channel('user.login.{companyId}', function ($user, $companyId) {
	if((int) $user->company()->id === (int) $companyId){
		return $user;
	}
});

Broadcast::channel('user.logout.{companyId}', function ($user, $companyId) {
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
