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
        $this->startSession($event->data());

        $fd = $event->fd();

        if (
            ($userId = Session::get(LAuth::getName())) &&
            ($user = LAuth::getProvider()->retrieveById($userId)) &&
            $user->password == Session::get('password_hash')
        ) {
            $event->clients()->set($fd, [
                'user_id' => $userId,
            ]);
            $event->users()->set($userId, [
                'fd' => $fd,
                'session_id' => Session::getId(),
            ]);
            $res = 'ok';
        } else {
            $res = 'failed';
        }
        $event->server->push($fd, Data::encode(Event::AUTH, $res));
    }

    protected function startSession(string $key)
    {
        Session::setId(Crypt::decrypt($key, false));
        Session::flush();
        Session::start();
    }
}
