<?php

namespace App\ChatServer\Listeners;

use App\ChatServer\Events\Auth as AuthEvent;
use App\ChatServer\Events\Event;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth as LAuth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class Auth
{
    /**
     * Handle the event.
     *
     * @param \App\ChatServer\Events\Auth $event
     *
     * @return void
     */
    public function handle(AuthEvent $event)
    {
        $this->startSession($event->data() ?: '');

        $fd = $event->fd();

        if (
            ($userId = Session::get(LAuth::getName())) &&
            ($user = LAuth::getProvider()->retrieveById($userId)) &&
            $user->password == Session::get('password_hash')
        ) {
            $users = $event->users();
            $clients = $event->clients();

            $oldFd = $users->get($userId, 'fd');
            if ($oldFd && ($oldFd != $fd)) {
                $clients->set($oldFd, ['user_id' => 0]);
                $event->ws->push($oldFd, Event::OTHER_LOGGED_IN);
            }

            $clients->set($fd, [
                'user_id' => $userId,
            ]);
            $users->set($userId, [
                'fd' => $fd,
                'session_id' => Session::getId(),
            ]);
            $res = 'ok';
        } else {
            $res = 'failed';
        }
        $event->ws->push($fd, Event::AUTH, $res);
    }

    protected function startSession(string $key)
    {
        try {
            Session::setId(Crypt::decrypt($key, false));
        } catch (DecryptException $e) {
        }
        Session::flush();
        Session::start();
    }
}
