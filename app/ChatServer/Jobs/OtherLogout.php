<?php

namespace App\ChatServer\Jobs;

use App\ChatServer\Data;
use App\ChatServer\Events\Event;
use App\ChatServer\WebSocketServer;
use Illuminate\Contracts\Queue\ShouldQueue;

class OtherLogout implements ShouldQueue
{
    public $queue = 'ws';
    /**
     * @var int
     */
    public $userId;
    /**
     * @var string
     */
    public $sessionId;

    /**
     * Create a new job instance.
     *
     * @param int $userId
     * @param string $sessionId
     *
     * @return void
     */
    public function __construct(int $userId, string $sessionId)
    {
        $this->userId = $userId;
        $this->sessionId = $sessionId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ws = app(WebSocketServer::class);
        $users = $ws->users;
        $clients = $ws->clients;

        $oldSessionId = $users->get($this->userId, 'session_id');

        // 如果 ws 中用户的 session id，和这次事件中的不一样，说明不是该设备登录的
        if ($oldSessionId && ($oldSessionId != $this->sessionId)) {
            if ($fd = $users->get($this->userId, 'fd')) {
                $clients->set($fd, [
                    'user_id' => 0,
                ]);

                $ws->server->push($fd, Data::encode(Event::OTHER_LOGGED_IN));
            }
            $users->del($this->userId);
        }
    }
}
