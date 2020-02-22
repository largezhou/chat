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
     * @param \App\Models\UserFriend $record
     *
     * @return void
     */
    public function created(UserFriend $record)
    {
        Notification::send(User::find($record->friend_id), new FriendRequested($record->id));
    }
}
