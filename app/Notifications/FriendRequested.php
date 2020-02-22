<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class FriendRequested extends Notification
{
    /**
     * @var int
     */
    protected $recordId;

    /**
     * Create a new notification instance.
     *
     * @param int $recordId
     *
     * @return void
     */
    public function __construct(int $recordId)
    {
        $this->recordId = $recordId;
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
            'record_id' => $this->recordId,
        ];
    }
}
