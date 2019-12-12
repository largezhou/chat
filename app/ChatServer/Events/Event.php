<?php

namespace App\ChatServer\Events;

use App\ChatServer\Data;
use App\ChatServer\WebSocketServer;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

abstract class Event
{
    const CONNECTED = 'connected';
    const PING = 'ping';
    const PONG = 'pong';
    const ONLINE_COUNT = 'online_count';
    const OTHER_LOGGED_IN = 'other_logged_in';
    const AUTH = 'auth';
    /**
     * @var \Swoole\WebSocket\Server
     */
    public $server;
    /**
     * @var \Swoole\WebSocket\Frame
     */
    public $frame;
    /**
     * @var \App\ChatServer\WebSocketServer
     */
    public $ws;

    public function __construct(Server $server, Frame $frame, WebSocketServer $ws)
    {
        $this->ws = $ws;
        $this->server = $server;
        $this->frame = $frame;
    }

    public function fd(): int
    {
        return $this->frame->fd;
    }

    public function clients(): \Swoole\Table
    {
        return $this->ws->clients;
    }

    public function users(): \Swoole\Table
    {
        return $this->ws->users;
    }

    public function data()
    {
        return Data::decode($this->frame->data)['data'];
    }
}
