<?php

namespace App\Admin\Requests;

use Illuminate\Support\Arr;

class UserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = $this->route('user');
        $id = (int) optional($user)->id;
        $rules = [
            'username' => ['bail', 'required', 'string', 'between:6,20', 'unique:users,username,'.$id],
            'name' => ['bail', 'required', 'string', 'max:20'],
            'avatar' => ['nullable', 'string', 'max:255'],
            'password' => ['bail', 'required', 'string', 'between:6,20', 'confirmed'],
        ];

        if ($this->isMethod('put')) {
            $rules = Arr::only($rules, $this->keys());
            // 更新时，没写密码，则不验证
            if (!$this->post('password')) {
                unset($rules['password']);
            }
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'username' => '账号',
            'name' => '昵称',
            'avatar' => '头像',
            'password' => '密码',
        ];
    }
}
