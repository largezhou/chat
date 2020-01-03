<?php

namespace App\Models;

use App\Traits\BothUsers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Msg extends Model
{
    use BothUsers;
    public const UPDATED_AT = null;
    public const MAX_GET_MSGS_COUNT = 50;
    public $incrementing = false;
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    protected $fillable = [
        'user_id', 'target_id', 'content', 'content_text',
    ];
    protected $casts = [
        'content' => 'json',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $model) {
            $model->{$model->getKeyName()} = Str::uuid()->toString();
            $model->content_text = $model->getContentText();
        });
    }

    protected function getContentText(): string
    {
        return trim(array_reduce($this->content, function ($carry, $i) {
            return is_array($i)
                ? $carry
                : $carry.$i;
        }, ''));
    }

    /**
     * 获取两人的对话记录
     *
     * @param int $userId
     * @param int $targetId
     * @param int|null $lastId
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getFriendsMsgsBy(
        int $userId,
        int $targetId,
        int $lastId = null
    ): \Illuminate\Contracts\Pagination\LengthAwarePaginator {
        return static::query()
            ->when($lastId, function (Builder $builder) use ($lastId) {
                return $builder->where('id', '<', $lastId);
            })
            ->bothUsers($userId, $targetId, 'user_id', 'target_id')
            ->orderByDesc('id')
            ->paginate(static::MAX_GET_MSGS_COUNT);
    }
}
