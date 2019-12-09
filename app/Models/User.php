<?php

namespace App\Models;

use App\Admin\Traits\ModelHelpers;
use Illuminate\Contracts\Auth\MustVerifyEmail;
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
}
