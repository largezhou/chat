<?php

namespace App\ChatServer\Listeners;

use App\ChatServer\Data;
use App\ChatServer\Events\Event;

class OnlineCount
{
    /**
     * Handle the event.
     *
     * @param \App\ChatServer\Events\Event $event
     *
     * @return void
     */
    public function handle($event)
    {
        $event->server->push(
            $event->fd(),
            Data::encode(Event::ONLINE_COUNT, $event->clients()->count())
        );
    }
}
