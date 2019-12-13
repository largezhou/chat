<?php

namespace App\Listeners;

use App\ChatServer\Jobs\OtherLogout;
use Illuminate\Auth\Events\OtherDeviceLogout;
use Illuminate\Support\Facades\Session;

class DispatchOtherLogoutToWS
{
    /**
     * Handle the event.
     *
     * @param \Illuminate\Auth\Events\OtherDeviceLogout $event
     *
     * @return void
     */
    public function handle(OtherDeviceLogout $event)
    {
        dispatch(new OtherLogout($event->user->id, Session::getId()));
    }
}
