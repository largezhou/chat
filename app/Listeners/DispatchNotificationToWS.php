<?php

namespace App\Listeners;

use App\ChatServer\Jobs\Notify;
use Illuminate\Notifications\Events\NotificationSent;

class DispatchNotificationToWS
{
    /**
     * Handle the event.
     *
     * @param \Illuminate\Notifications\Events\NotificationSent $event
     *
     * @return void
     */
    public function handle(NotificationSent $event)
    {
        dispatch(new Notify($event));
    }
}
