<?php

namespace App\Models;

use App\Admin\Traits\ModelHelpers;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

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

    public static function createUser($inputs)
    {
        $inputs = array_merge($inputs, [
            'password' => Hash::make($inputs['password']),
        ]);
        return static::create($inputs);
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
     * 所有朋友的 id
     *
     * @param boolean $onlyAccepted
     *
     * @return array|int[]
     */
    public function friendIds(bool $onlyAccepted = true): array
    {
        $records = UserFriend::query()
            ->when($onlyAccepted, function (Builder $query) {
                $query->where('accepted', true);
            })
            ->where(function (Builder $query) {
                $query->where('friend_id', $this->id)
                    ->orWhere('user_id', $this->id);
            })
            ->pluck('user_id', 'friend_id');

        return $records
            ->merge($records->keys())
            ->filter(function ($i) {
                return $i != $this->id;
            })
            ->values()
            ->toArray();
    }
}
