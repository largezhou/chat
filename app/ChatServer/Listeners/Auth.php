<?php

namespace App\ChatServer\Listeners;

use App\ChatServer\Data;
use App\ChatServer\Events\Event;
use Illuminate\Support\Facades\Auth as LAuth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class Auth
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
        Session::setId(Crypt::decrypt($event->data(), false));
        Session::start();

        $fd = $event->fd();

        if ($userId = LAuth::id()) {
            $event->clients()->set($fd, [
                'user_id' => $userId,
            ]);
            $event->users()->set($userId, [
                'fd' => $fd,
            ]);
        } else {
            $event->server->push($fd, Data::encode(Event::AUTH, 'failed'));
        }
    }
}
