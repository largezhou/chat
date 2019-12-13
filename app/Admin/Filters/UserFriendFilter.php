<?php

namespace App\Admin\Filters;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserFriendFilter extends Filter
{
    protected $filters = ['friends_of'];

    /**
     * 朋友筛选，双向
     *
     * @param $val
     */
    public function friendsOf($val)
    {
        $userId = User::where('name', 'like', "%{$val}%")->value('id');
        if ($userId) {
            $this->builder->where(function (Builder $query) use ($userId) {
                $query->where('user_id', $userId)->orWhere('friend_id', $userId);
            });
        } else {
            $this->builder->where('id', -1);
        }
    }
}
