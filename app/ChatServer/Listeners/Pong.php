<?php

namespace App\ChatServer\Listeners;

use App\ChatServer\Events\Event;

class Pong
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
        $event->ws->push(
            $event->fd(),
            Event::PONG
        );
    }
}
