<?php

namespace App\Models;

use App\Admin\Traits\ModelHelpers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserFriend extends Model
{
    use ModelHelpers;
    protected $table = 'user_friend';
    protected $casts = [
        'accepted' => 'bool',
    ];

    /**
     * 邀请人
     */
    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * 被邀请人
     */
    public function invitee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'friend_id');
    }

    public static function isFriend(int $userId, int $targetId, bool $accepted = true): bool
    {
        return static::query()
            ->where(function (Builder $query) use ($userId, $targetId) {
                $query
                    ->where([
                        'user_id' => $userId,
                        'friend_id' => $targetId,
                    ])
                    ->orWhereRaw('(`user_id` = ? AND `friend_id` = ?)', [$targetId, $userId]);
            })
            ->where('accepted', $accepted)
            ->exists();
    }
}
