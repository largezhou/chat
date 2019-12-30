<?php

namespace App\Providers;

use App\Listeners\AddSessionIdToEvent;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        \App\ChatServer\Events\Ping::class => [
            \App\ChatServer\Listeners\Pong::class,
        ],

        \App\ChatServer\Events\OnlineCount::class => [
            \App\ChatServer\Listeners\OnlineCount::class,
        ],

        \App\ChatServer\Events\Auth::class => [
            \App\ChatServer\Listeners\Auth::class,
            \App\ChatServer\Listeners\OnlineFriendIds::class,
        ],

        \Illuminate\Auth\Events\OtherDeviceLogout::class => [
            \App\Listeners\DispatchOtherLogoutToWS::class,
        ],

        \Illuminate\Auth\Events\Logout::class => [
            \App\Listeners\DispatchLogoutToWS::class,
        ],

        \App\ChatServer\Events\Msg::class => [
            \App\ChatServer\Listeners\HandleMsg::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
