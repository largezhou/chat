<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * @method $this|\Illuminate\Database\Eloquent\Builder bothUsers(int $userId, int $targetId, string $userField = 'user_id', string $targetField = 'target_id')
 */
trait BothUsers
{
    /**
     * 封装一个
     * `(user_id = 1 AND target_id = 2) OR (user_id = 2 AND target_id = 1)`
     * 的查询
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param int $userId
     * @param int $targetId
     * @param string $userField
     * @param string $targetField
     *
     * @return $this|\Illuminate\Database\Eloquent\Builder
     */
    public function scopeBothUsers(
        Builder $builder,
        int $userId,
        int $targetId,
        string $userField = 'user_id',
        string $targetField = 'target_id'
    ): Builder {
        return $builder->where(function (Builder $builder) use ($userId, $targetId, $userField, $targetField) {
            $builder
                ->where([
                    $userField => $userId,
                    $targetField => $targetId,
                ])
                ->orWhere(function (Builder $builder) use ($userId, $targetId, $userField, $targetField) {
                    $builder->where([
                        $userField => $targetId,
                        $targetField => $userId,
                    ]);
                });
        });
    }
}
