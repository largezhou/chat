<?php

namespace App\ChatServer\Jobs;

use App\ChatServer\EventEnum;
use App\ChatServer\WebSocketServer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Events\NotificationSent;

class Notify implements ShouldQueue
{
    public $queue = 'ws';

    /**
     * @var \Illuminate\Notifications\Events\NotificationSent $event
     */
    public $event;

    /**
     * @param \Illuminate\Notifications\Events\NotificationSent $event
     */
    public function __construct(NotificationSent $event)
    {
        $this->event = $event;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $e = $this->event;
        $ws = app(WebSocketServer::class);
        if ($fd = $ws->users->get($e->notifiable->id, 'fd')) {
            // 数据库通知时，response 为通知模型实例
            $ws->push($fd, EventEnum::NOTIFY, $e->response);
        }
    }
}
