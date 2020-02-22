<?php

namespace App\Models;

use App\Admin\Traits\ModelHelpers;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Class User
 * @package App\Models
 * @method $this|\Illuminate\Database\Eloquent\Builder isNotFriend(int $userId)
 */
class User extends Authenticatable
{
    use Notifiable;
    use ModelHelpers;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'name', 'avatar', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $user) {
            $user->name = $user->name ?: $user->username;
            $user->password = Hash::make($user->password);
        });
    }

    public function updateUser($inputs)
    {
        if (isset($inputs['password'])) {
            $inputs['password'] = Hash::make($inputs['password']);
        }

        return $this->update($inputs);
    }

    /**
     * 我添加的朋友
     */
    public function friendsOfMine(): BelongsToMany
    {
        return $this->belongsToMany(static::class, 'user_friend', 'user_id', 'friend_id')
            ->withPivot(['created_at', 'accepted']);
    }

    /**
     * 添加我的朋友
     */
    public function friendsOf(): BelongsToMany
    {
        return $this->belongsToMany(static::class, 'user_friend', 'friend_id', 'user_id')
            ->withPivot(['created_at', 'accepted']);
    }

    /**
     * 所有朋友
     *
     * @param bool $onlyAccepted
     *
     * @return Collection|static[]
     */
    public function friends(bool $onlyAccepted = true): Collection
    {
        $mine = $this->friendsOfMine();
        $other = $this->friendsOf();

        if ($onlyAccepted) {
            $mine->wherePivot('accepted', true);
            $other->wherePivot('accepted', true);
        }

        return $mine->get()->merge($other->get());
    }

    /**
     * 不是朋友筛选
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param int $userId
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsNotFriend(Builder $builder, int $userId): Builder
    {
        return $builder->whereNotExists(function (QueryBuilder $query) use ($userId) {
            $query->select(DB::raw(1))
                ->from('user_friend', 'uf')
                ->where('accepted', true)
                ->where(function (QueryBuilder $query) use ($userId) {
                    $query->whereRaw('(uf.user_id = users.id AND uf.friend_id = ?)', [$userId])
                        ->orWhereRaw('(uf.friend_id = users.id AND uf.user_id = ?)', [$userId]);
                });
        });
    }

    public static function hasNotifications()
    {
        return Auth::user() ? Auth::user()->notifications()->exists() : false;
    }
}
