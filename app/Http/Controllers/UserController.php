<?php

namespace App\Http\Controllers;

use App\Admin\Resources\UserResource;
use App\Admin\Traits\RestfulResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use RestfulResponse;

    public function info()
    {
        return Auth::user();
    }

    public function friends()
    {
        $user = Auth::user();
        $friends = $user->friends()
            ->sortBy('pivot.accepted')
            ->sortByDesc('pivot.created_at');

        return $this->ok(UserResource::collection($friends));
    }
}
