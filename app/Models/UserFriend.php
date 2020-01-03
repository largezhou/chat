<?php

namespace App\Models;

use App\Admin\Traits\ModelHelpers;
use App\Traits\BothUsers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public static function isFriend(int $userId, int $targetId, bool $accepted = true): bool
    {
        return static::query()
            ->bothUsers($userId, $targetId, 'user_id', 'friend_id')
            ->where('accepted', $accepted)
            ->exists();
    }
}
