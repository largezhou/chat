<?php

namespace App\Console\Commands;

use App\ChatServer\WebSocketServer;
use Illuminate\Console\Command;

class ChatServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat:serve';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '启动聊天服务';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        app(WebSocketServer::class, ['console' => $this])->start();
    }
}
