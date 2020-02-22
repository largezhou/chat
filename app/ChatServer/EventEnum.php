<?php

namespace App\ChatServer;

class EventEnum
{
    public const CONNECTED = 'connected';
    public const PING = 'ping';
    public const PONG = 'pong';
    public const ONLINE_COUNT = 'online_count';
    public const OTHER_LOGGED_IN = 'other_logged_in';
    public const AUTH = 'auth';
    public const ONLINE_FRIEND_IDS = 'online_friend_ids';
    public const FRIEND_ONLINE = 'friend_online';
    public const FRIEND_OFFLINE = 'friend_offline';
    public const MSG_RES = 'msg_res';
    public const MSG = 'msg';
    public const NOTIFY = 'notify';
}
