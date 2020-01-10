<?php

namespace App\Observers;

use App\Models\Msg;
use App\Models\RecentContact;

class MsgObserver
{
    /**
     * Handle the msg "created" event.
     *
     * @param \App\Models\Msg $msg
     *
     * @return void
     */
    public function created(Msg $msg)
    {
        RecentContact::record($msg);
    }
}
