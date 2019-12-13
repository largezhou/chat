<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
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

    use AuthenticatesUsers;

    protected function authenticated(Request $request, $user)
    {
        Auth::logoutOtherDevices($request->input('password'));
        $request->session()->put([
            'password_hash' => $user->password,
        ]);

        return response()->json($user);
    }

    public function username()
    {
        return 'username';
    }

    public function loggedOut(Request $request)
    {
        return response(null, 204);
    }
}
