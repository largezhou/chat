<?php

namespace App\ChatServer\Events;

use App\ChatServer\WebSocketServer;
use Swoole\WebSocket\Frame;

abstract class Event
{
    /**
     * @var \App\ChatServer\WebSocketServer
     */
    public $ws;

    /**
     * @var \Swoole\WebSocket\Frame
     */
    public $frame;

    public function __construct(WebSocketServer $ws, Frame $frame)
    {
        $this->ws = $ws;
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
        return $this->ws->data($this->frame->data)['data'];
    }
}
