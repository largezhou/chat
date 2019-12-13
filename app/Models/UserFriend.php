<?php

namespace App\Models;

use App\Admin\Traits\ModelHelpers;
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
}
