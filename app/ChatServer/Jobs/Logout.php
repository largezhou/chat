<?php

namespace App\ChatServer\Jobs;

use App\ChatServer\WebSocketServer;
use Illuminate\Contracts\Queue\ShouldQueue;

class Logout implements ShouldQueue
{
    public $queue = 'ws';
    /**
     * @var int
     */
    public $userId;

    /**
     * Create a new job instance.
     *
     * @param int $userId
     *
     * @return void
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ws = app(WebSocketServer::class);

        $fd = $ws->users->get($this->userId, 'fd');
        $ws->users->del($this->userId);

        if ($fd) {
            $ws->clients->set($fd, ['user_id' => 0]);
        }
    }
}
