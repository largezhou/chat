<?php

namespace App\Providers;

use App\Models\Msg;
use App\Models\UserFriend;
use App\Observers\MsgObserver;
use App\Observers\UserFriendObserver;
use Illuminate\Support\ServiceProvider;

class ModelEventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Msg::observe(MsgObserver::class);
        UserFriend::observe(UserFriendObserver::class);
    }
}
