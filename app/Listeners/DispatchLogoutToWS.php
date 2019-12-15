<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout as LogoutEvent;
use App\ChatServer\Jobs\Logout;

class DispatchLogoutToWS
{
    /**
     * Handle the event.
     *
     * @param \Illuminate\Auth\Events\Logout $event
     *
     * @return void
     */
    public function handle(LogoutEvent $event)
    {
        dispatch(new Logout($event->user->id));
    }
}
