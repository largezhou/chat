<?php

namespace App\Observers;

use App\Models\User;
use App\Models\UserFriend;
use App\Notifications\FriendRequested;
use Illuminate\Support\Facades\Notification;

class UserFriendObserver
{
    /**
     * Handle the msg "created" event.
     *
     * @param \App\Models\UserFriend $userFriend
     *
     * @return void
     */
    public function created(UserFriend $userFriend)
    {
        Notification::send(
            User::find($userFriend->friend_id),
            new FriendRequested($userFriend->id, $userFriend->user_id)
        );
    }
}
