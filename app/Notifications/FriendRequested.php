<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class FriendRequested extends Notification
{
    /**
     * @var int
     */
    protected $userFriendId;
    /**
     * @var int
     */
    protected $inviterId;

    /**
     * Create a new notification instance.
     *
     * @param int $userFriendId 好友关系记录 ID
     * @param int $inviterId 邀请人 ID
     *
     * @return void
     */
    public function __construct(int $userFriendId, int $inviterId)
    {
        $this->userFriendId = $userFriendId;
        $this->inviterId = $inviterId;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'user_friend_id' => $this->userFriendId,
            'inviter_id' => $this->inviterId,
        ];
    }
}
