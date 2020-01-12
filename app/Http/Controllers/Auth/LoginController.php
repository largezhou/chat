<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {
        sendFailedLoginResponse as originSendFailedLoginResponse;
    }

    protected function authenticated(Request $request, $user)
    {
        Auth::logoutOtherDevices($request->input('password'));

        return $this->created(UserResource::make($user));
    }

    public function username()
    {
        return 'username';
    }

    public function loggedOut(Request $request)
    {
        return $this->noContent();
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        // 登录失败，则尝试注册
        $exists = User::query()
            ->where($this->username(), $request->input($this->username()))
            ->exists();

        // 如果帐号已经存在，则返回登录失败
        if ($exists) {
            $this->originSendFailedLoginResponse($request);
        }

        // 注册并登录
        $request->validate([
            $this->username() => ['bail', 'required', 'string', 'between:6,20'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $user = User::create([
            $this->username() => $request->input($this->username()),
            'password' => $request->input('password'),
        ]);

        $this->guard()->login($user);

        // 返回密码到前端做提示
        $user->password = $request->input('password');
        $user->setHidden([]);
        return $this->created(UserResource::make($user));
    }
}
