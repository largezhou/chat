<?php

namespace App\ChatServer;

use App\ChatServer\Events\Event;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Swoole\Http\Request;
use Swoole\Table;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

class WebSocketServer
{
    /**
     * @var \Swoole\WebSocket\Server
     */
    protected $server;
    /**
     * @var \Illuminate\Console\Command
     */
    protected $console;
    protected $events = [
        'start', 'message', 'open', 'close', 'workerStart',
    ];
    /**
     * 存储客户端相关的数据
     *
     * @var \Swoole\Table
     */
    public $clients;
    /**
     * @var array
     */
    protected $config = [];
    /**
     * 存储用户相关数据
     *
     * @var \Swoole\Table
     */
    public $users;

    /**
     * @param \Illuminate\Console\Command $console
     */
    public function __construct(Command $console)
    {
        $this->console = $console;

        $this->config = $config = config('ws');
        $this->server = new Server($config['host'], $config['port']);
        $this->server->set([
            'worker_num' => $config['worker_num'],
            'heartbeat_check_interval' => $config['interval'],
            'heartbeat_idle_time' => $config['timeout'],
            'log_file' => storage_path('logs/chat_server.log'),
        ]);

        $this->createClientsTable();
        $this->createUsersTable();

        $this->initEvents();
    }

    protected function initEvents()
    {
        foreach ($this->events as $e) {
            $this->server->on(strtolower($e), function () use ($e) {
                $this->{'on'.ucfirst($e)}(...func_get_args());
            });
        }
    }

    protected function onStart(Server $server)
    {
        $this->console->info("已启动：{$server->host}:{$server->port}");
    }

    protected function onMessage(Server $server, Frame $frame)
    {
        $type = Data::decode($frame->data)['type'];
        $eventClass = '\\App\\ChatServer\\Events\\'.Str::studly($type);
        event(new $eventClass($server, $frame, $this));
    }

    protected function onOpen(Server $server, Request $request)
    {
        $fd = $request->fd;

        $this->clients->set($fd, []);

        $server->push($fd, Data::encode(Event::CONNECTED, [
            'interval' => $this->config['interval'],
            'timeout' => $this->config['timeout'],
        ]));
    }

    protected function onClose(Server $server, $fd)
    {
        $this->clients->del($fd);
    }

    public function start()
    {
        $this->server->start();
    }

    protected function createClientsTable()
    {
        $clients = $this->clients = new Table(1024);
        $clients->column('user_id', Table::TYPE_INT, 20);
        $clients->create();
    }

    protected function createUsersTable()
    {
        $users = $this->users = new Table(1024);
        $users->column('fd', Table::TYPE_INT, 10);
        $users->create();
    }

    protected function onWorkerStart(Server $server, int $workerId)
    {
        $this->console->info("{$workerId} 号开工了");
    }
}
