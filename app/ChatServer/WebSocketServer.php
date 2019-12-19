<?php

namespace App\ChatServer;

use App\ChatServer\Events\Event;
use Illuminate\Console\Command;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Swoole\Http\Request;
use Swoole\Table;
use Swoole\Timer;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

class WebSocketServer
{
    const MAX_LARAVEL_QUEUE_WORKER = 4;
    const PID_FILE = __DIR__.'/ws.pid';
    /**
     * @var \Swoole\WebSocket\Server
     */
    public $server;
    /**
     * @var \Illuminate\Console\Command
     */
    public $console;
    protected $events = [
        'start', 'message', 'open', 'close', 'workerStart', 'task',
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
     * @var \Illuminate\Foundation\Application
     */
    protected $app;
    /**
     * 用于执行 laravel queue:work 的 task id
     *
     * @var array
     */
    protected $laravelQueueWorkIds = [];

    /**
     * @param \Illuminate\Console\Command $console
     * @param \Illuminate\Foundation\Application $app
     */
    public function __construct(Command $console, Application $app)
    {
        $this->console = $console;
        $this->app = $app;

        $this->config = $config = config('ws');

        $this->setLaravelQueueWorkIds();

        $this->server = new Server($config['host'], $config['port']);
        $this->server->set([
            'worker_num' => $config['worker_num'],
            'task_worker_num' => $config['task_worker_num'],
            'heartbeat_check_interval' => $config['interval'],
            'heartbeat_idle_time' => $config['timeout'],
            'log_file' => storage_path('logs/chat_server.log'),
            'pid_file' => self::PID_FILE,
        ]);

        $this->createClientsTable();
        $this->createUsersTable();

        $this->initEvents();
    }

    protected function setLaravelQueueWorkIds()
    {
        $num = $this->config['task_worker_num'];
        if ($num <= 1) {
            throw new \Exception('[ task_worker_num ] 至少要设置为 2');
        }

        if ($num <= static::MAX_LARAVEL_QUEUE_WORKER) {
            $num -= 1;
        } else {
            $num = static::MAX_LARAVEL_QUEUE_WORKER;
        }

        $this->laravelQueueWorkIds = range(0, $num - 1);
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
        $this->startLaravelQueueWorkTask($server);

        $this->console->info("已启动：{$server->host}:{$server->port}");
    }

    protected function startLaravelQueueWorkTask(Server $server)
    {
        foreach ($this->laravelQueueWorkIds as $workId) {
            $server->task('queue:work', $workId);
        }
    }

    protected function onMessage(Server $server, Frame $frame)
    {
        $type = $this->data($frame->data)['type'];
        $eventClass = '\\App\\ChatServer\\Events\\'.Str::studly($type);

        if (!class_exists($eventClass)) {
            $this->console->error("[ {$type} ] 事件不存在");
        } else {
            event(new $eventClass($this, $frame));
        }
    }

    protected function onOpen(Server $server, Request $request)
    {
        $fd = $request->fd;

        $this->clients->set($fd, []);

        $this->push($fd, Event::CONNECTED, [
            'interval' => $this->config['interval'],
            'timeout' => $this->config['timeout'],
        ]);
    }

    protected function onClose(Server $server, int $fd)
    {
        if ($userId = $this->clients->get($fd, 'user_id')) {
            $this->users->del($userId);
        }
        $this->clients->del($fd);
    }

    public function start()
    {
        $this->cleans();
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
        $users->column('session_id', Table::TYPE_STRING, 100);
        $users->create();
    }

    protected function onWorkerStart(Server $server, int $workerId)
    {
        $this->console->info("{$workerId} 号开工了");
    }

    protected function onTask(Server $server, int $taskId, int $fromId, string $data)
    {
        $this->runLaravelQueueWork($taskId);
    }

    protected function runLaravelQueueWork(int $taskId)
    {
        if (in_array($taskId, $this->laravelQueueWorkIds)) {
            $this->app->instance(static::class, $this);
            Artisan::call('queue:work --queue=ws');
        }
    }

    public function push($fd, string $type, $data = null)
    {
        $this->server->push($fd, Data::encode($type, $data));
    }

    public function data(string $data): array
    {
        return Data::decode($data);
    }

    protected function cleans()
    {
        DB::disableQueryLog();
        DB::disconnect();
    }
}
