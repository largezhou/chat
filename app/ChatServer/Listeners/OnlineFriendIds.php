<?php

namespace App\ChatServer\Listeners;

use App\ChatServer\EventEnum;
use App\ChatServer\Events\Event;
use App\Models\UserFriend;

class OnlineFriendIds
{
    /**
     * Handle the event.
     *
     * @param \App\ChatServer\Events\Event $event
     *
     * @return void
     */
    public function handle(Event $event)
    {
        $fd = $event->fd();

        if (!$userId = $event->clients()->get($fd)['user_id']) {
            return;
        }

        $onlineUserIds = [];
        foreach ($event->users() as $i => $_) {
            $onlineUserIds[] = (int) $i;
        }

        $friendIds = UserFriend::friendIdsOf($userId);

        $onlineFriendIds = array_values(array_intersect($onlineUserIds, $friendIds));

        // 获取当前用户的在线好友状态
        $event->ws->push($fd, EventEnum::ONLINE_FRIEND_IDS, $onlineFriendIds);

        // 通知当前用户的好友用户已上线
        foreach ($onlineFriendIds as $id) {
            if ($friendFd = $event->users()->get($id, 'fd')) {
                $event->ws->push($friendFd, EventEnum::FRIEND_ONLINE, $userId);
            }
        }
    }
}
