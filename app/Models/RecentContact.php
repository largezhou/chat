<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecentContact extends Model
{
    public const MAX_CONTENT_LEN = 50;
    protected $fillable = [
        'msg_id', 'user_id', 'target_id', 'msg',
    ];

    /**
     * 记录最近联系人
     *
     * @param \App\Models\Msg $msg
     */
    public static function record(Msg $msg): void
    {
        $data = [
            'msg_id' => $msg->uuid,
            // 没有纯文字内容，肯定是有图片
            'msg' => substr($msg->content_text ?: '[图片]', 0, static::MAX_CONTENT_LEN),
        ];

        static::updateOrCreate(
            [
                'user_id' => $msg->user_id,
                'target_id' => $msg->target_id,
            ],
            $data
        );
        static::updateOrCreate(
            [
                'user_id' => $msg->target_id,
                'target_id' => $msg->user_id,
            ],
            $data
        );
    }

    /**
     * @param int $userId
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getContactsBy(int $userId): Collection
    {
        return static::query()
            ->with(['target'])
            ->where('user_id', $userId)
            ->orderByDesc('updated_at')
            ->get();
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_id');
    }
}
