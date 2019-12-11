<?php

namespace App\ChatServer;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Swoole\Http\Request;
use Swoole\Table;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

class WebSocketServer
{
    const E_CONNECTED = 'connected';
    const E_PING = 'ping';
    const E_PONG = 'pong';
    const E_ONLINE_COUNT = 'online_count';
    const E_LOGIN_OTHER = 'login_other';
    const E_AUTH = 'auth';
    const INTERVAL = 30;
    const TIMEOUT = self::INTERVAL * 2;
    /**
     * @var \Swoole\WebSocket\Server
     */
    protected $server;
    /**
     * @var \Illuminate\Console\Command
     */
    protected $console;
    protected $events = [
        'start', 'message', 'open', 'close',
    ];
    /**
     * 存储客户端相关的数据
     *
     * @var \Swoole\Table
     */
    protected $clients;
    /**
     * @var array
     */
    protected $config = [];
    /**
     * 存储用户相关数据
     *
     * @var \Swoole\Table
     */
    protected $users;

    /**
     * @param \Illuminate\Console\Command $console
     */
    public function __construct(Command $console)
    {
        $this->config = $config = config('ws');
        $this->console = $console;
        $this->server = new Server($config['host'], $config['port']);
        $this->server->set([
            'worker_num' => $config['worker_num'],
            'heartbeat_check_interval' => static::INTERVAL,
            'heartbeat_idle_time' => static::TIMEOUT,
            'log_file' => storage_path('logs/chat_server.log'),
        ]);

        $this->createClientsTable();
        $this->createUsersTable();

        $this->initEvents();
    }

    protected function initEvents()
    {
        foreach ($this->events as $e) {
            $this->server->on($e, function () use ($e) {
                $this->{'on'.ucfirst($e)}(...func_get_args());
            });
        }
    }

    protected function onStart(Server $server)
    {
        $this->console->info('已启动');
    }

    protected function onMessage(Server $server, Frame $frame)
    {
        $fd = $frame->fd;

        $data = $this->decodeData($frame->data);
        $type = $data['type'];
        $data = $data['data'];

        switch ($type) {
            case static::E_PING:
                $server->push($fd, $this->encodeData(static::E_PONG));
                break;
            case static::E_ONLINE_COUNT:
                $server->push($fd, $this->encodeData(static::E_ONLINE_COUNT, $this->clients->count()));
                break;
            case static::E_AUTH:
                Session::setId(Crypt::decrypt($data, false));
                Session::start();

                if ($userId = Auth::id()) {
                    $oldFd = $this->users->get($userId, 'fd');
                    if ($oldFd) {
                        $this->clients->del($oldFd);
                        $server->push($oldFd, $this->encodeData(static::E_LOGIN_OTHER));
                    }
                    $this->clients->set($fd, [
                        'user_id' => $userId,
                    ]);
                    $this->users->set($userId, [
                        'fd' => $fd,
                    ]);
                } else {
                    $server->push($fd, $this->encodeData(static::E_AUTH, 'failed'));
                }
                break;
            default:
                // do nothing
        }
    }

    protected function onOpen(Server $server, Request $request)
    {
        $fd = $request->fd;

        $this->addClient($server, $fd);

        $server->push($fd, $this->encodeData(static::E_CONNECTED, [
            'interval' => static::INTERVAL,
            'timeout' => static::TIMEOUT,
        ]));
    }

    protected function onClose(Server $server, $fd)
    {
        $this->removeClient($fd);
    }

    public function start()
    {
        $this->server->start();
    }

    protected function encodeData(string $type, $data = null): string
    {
        return json_encode(compact('type', 'data'));
    }

    protected function decodeData(string $data)
    {
        $res = json_decode($data, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $res = [];
        }

        return [
            'type' => $res['type'] ?? '',
            'data' => $res['data'] ?? null,
        ];
    }

    protected function createClientsTable()
    {
        $clients = $this->clients = new Table(1024);
        $clients->column('user_id', Table::TYPE_INT, 20);
        $clients->create();
    }

    protected function addClient(Server $server, $fd)
    {
        $this->clients->set($fd, [
            'fd' => $fd,
        ]);
    }

    protected function removeClient($fd)
    {
        $this->clients->del($fd);
    }

    protected function createUsersTable()
    {
        $users = $this->users = new Table(1024);
        $users->column('fd', Table::TYPE_INT, 10);
        $users->create();
    }
}
