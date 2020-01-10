<?php

namespace App\ChatServer\Listeners;

use App\ChatServer\Events\Event;
use App\Models\Msg;
use App\Models\UserFriend;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HandleMsg
{
    protected const FOLDER = 'uploads/msg';
    /**
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected $disk;
    /**
     * @var \App\ChatServer\Events\Event
     */
    protected $event;

    public function __construct()
    {
        $this->disk = Storage::disk('uploads');
    }

    /**
     * Handle the event.
     *
     * @param \App\ChatServer\Events\Event $event
     *
     * @return void
     */
    public function handle(Event $event)
    {
        $this->event = $event;
        $fd = $event->fd();

        // TODO 放到任务中间件中？
        $userId = $event->clients()->get($fd, 'user_id');
        if (!$userId) {
            return;
        }

        $data = $event->data();

        if (!$this->validate($data)) {
            return;
        }

        $targetId = $data['target'] ?? null;
        if (!UserFriend::isFriend($userId, $targetId)) {
            $this->response('你们还不是好友。');
            return;
        }

        $content = $this->handleImages($data['content']);

        $msg = Msg::storeMsg($userId, $targetId, $content);

        $this->response();

        if ($targetFd = $event->users()->get($targetId, 'fd')) {
            $event->ws->push($targetFd, Event::MSG, $msg);
        }
    }

    protected function validate(array $data): bool
    {
        $v = Validator::make(
            $data,
            ['content' => 'required|array'],
            [],
            ['content' => '消息内容']
        );
        if ($v->fails()) {
            $this->response($v->getMessageBag()->first('content'));
            return false;
        }

        return true;
    }

    /**
     * @param string|null $error 没有错误，表示消息发送成功
     */
    protected function response(string $error = null)
    {
        $e = $this->event;

        $e->ws->push($e->fd(), Event::MSG_RES, [
            'key' => $e->data()['key'] ?? null,
            'error' => $error,
        ]);
    }

    protected function handleImages(array $content): array
    {
        $t = [];
        foreach ($content as $i) {
            if (is_string($i)) {
                $t[] = $i;
            } elseif (isset($i['type']) && isset($i['data'])) {
                $t[] = [
                    'type' => $i['type'],
                    'data' => $this->saveImage($i['data']),
                ];
            } else {
                // 丢弃不规范的值
                continue;
            }
        }

        return $t;
    }

    protected function saveImage(string $base64): string
    {
        [$ext, $img] = $this->decodeImage($base64);

        if (!$img) {
            return '';
        }

        $filename = uniqid(substr(md5($img), 0, 5)).($ext ? ".{$ext}" : '');
        $path = static::FOLDER.DIRECTORY_SEPARATOR.$filename;

        $this->disk->put($path, $img);

        return $path;
    }

    protected function decodeImage(string $base64): array
    {
        $matches = [];
        preg_match('/data:image\/(.*?);base64,(.*)/', $base64, $matches);
        return [
            $matches[1] ?? '',
            base64_decode($matches[2] ?? ''),
        ];
    }
}
