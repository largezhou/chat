<?php

namespace App\Models;

use App\Admin\Traits\ModelHelpers;
use App\Traits\BothUsers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method $this|\Illuminate\Database\Eloquent\Builder onlyAccepted(bool $onlyAccepted = true)
 */
class UserFriend extends Model
{
    use BothUsers;
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

    public function scopeOnlyAccepted(Builder $builder, bool $onlyAccepted = true)
    {
        $builder->when($onlyAccepted, function (Builder $builder) {
            $builder->where('accepted', true);
        });
    }

    public static function isFriend(int $userId, int $targetId, bool $onlyAccepted = true): bool
    {
        return static::query()
            ->bothUsers($userId, $targetId, 'user_id', 'friend_id')
            ->when($onlyAccepted, function (Builder $builder) use ($onlyAccepted) {
                return $builder->where('accepted', $onlyAccepted);
            })
            ->exists();
    }

    /**
     * 用户所有好友的 id
     *
     * @param int $userId
     * @param bool $onlyAccepted
     *
     * @return array
     */
    public static function friendIdsOf(int $userId, bool $onlyAccepted = true): array
    {
        return static::query()
            ->onlyAccepted($onlyAccepted)
            ->where(function (Builder $query) use ($userId) {
                $query->where('friend_id', $userId)
                    ->orWhere('user_id', $userId);
            })
            ->get()
            ->map(function (self $i) {
                return [$i->user_id, $i->friend_id];
            })
            ->flatten()
            ->unique()
            ->reject(function ($id) use ($userId) {
                return $id == $userId;
            })
            ->toArray();
    }
}
