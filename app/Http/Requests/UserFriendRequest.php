<?php

namespace App\Http\Requests;

use App\Models\UserFriend;
use App\Rules\IsNotFriend;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserFriendRequest extends FormRequest
{
    public function authorize()
    {
        $ur = $this->route('user_friend');
        if ($this->isMethod('put')) {
            return $ur->friend_id == Auth::id();
        } else {
            return true;
        }
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException('看起来这个好友邀请跟你无关。');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->isMethod('put')) {
            return [
                'accepted' => 'nullable',
            ];
        } else {
            $userId = Auth::id();
            $validTime = date('Y-m-d H:i:s', strtotime('-'.UserFriend::MIN_APPLY_INTERVAL.' days'));

            return [
                'friend_id' => [
                    'bail',
                    'required',
                    Rule::exists('users', 'id')->whereNot('id', $userId),
                    new IsNotFriend($userId),
                    // Rule::unique('user_friend')
                    //     ->where(function (Builder $builder) use ($validTime, $userId) {
                    //         $builder->where([
                    //             ['user_id', $userId],
                    //             ['accepted', false],
                    //             ['created_at', '>', $validTime], // N 天内只能申请一次
                    //         ]);
                    //     }),
                ],
            ];
        }
    }

    public function messages()
    {
        return [
            'friend_id.exists' => '用户不存在。',
            'friend_id.unique' => UserFriend::MIN_APPLY_INTERVAL.' 天内只能申请一次。',
        ];
    }
}
