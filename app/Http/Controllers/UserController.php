<?php

namespace App\Http\Controllers;

use App\Admin\Traits\RestfulResponse;
use App\Http\Resources\MsgResource;
use App\Http\Resources\UserResource;
use App\Models\Msg;
use App\Models\User;
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

    public function getFriendsMsgs(Request $request, User $friend)
    {
        $msgs = Msg::getFriendsMsgsBy(
            Auth::id(),
            $friend->id,
            (int) $request->input('last_id')
        );

        return $this->ok(MsgResource::collection($msgs));
    }
}
