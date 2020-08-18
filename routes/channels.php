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

use App\Broadcasting\UserChannel;

Broadcast::channel('activity.log.{id}', function ($user, $id) {
    return $user->hasRole('admin') && (int)$user->id === (int)$id;
});

Broadcast::channel('activity.log', function ($user) {
    return true;
});

Broadcast::channel('auto-logout', function ($user, $id) {
    return true;
});

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});

Broadcast::channel('chat.typing-{companyId}', function ($user, $companyId) {
    if ((int)$user->company()->id === (int)$companyId) {
        return $user->basics();
    }
});

Broadcast::channel('chat.new-message.{toId}', function ($user, $toId) {
    if ((int)$user->id === (int)$toId) {
        return $user->basics();
    }
});

Broadcast::channel('chat.notification.{toId}', function ($user, $toId) {
    if ((int)$user->id === (int)$toId) {
        return $user->basics();
    }
});

Broadcast::channel('comment.task.{taskId}', function ($comment, $taskId) {
    if ((int)$comment->commentable->id === (int)$taskId) {
        return $comment;
    }
});

//Channel pertaining to user
Broadcast::channel('as-user.{id}', UserChannel::class);
//Channel for company events
Broadcast::channel('as-company.{company_id}', \App\Broadcasting\CompanyChannel::class);

Broadcast::channel('project.client-message.{projectId}', \App\Broadcasting\ProjectClientChannel::class);

Broadcast::channel('project.team-message.{projectId}', \App\Broadcasting\ProjectTeamChannel::class);
//Channel for all/global events
Broadcast::channel('as-apps', \App\Broadcasting\PublicChannel::class);

