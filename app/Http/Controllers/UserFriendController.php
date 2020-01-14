<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserFriendRequest;
use App\Http\Resources\UserFriendResource;
use App\Models\UserFriend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserFriendController extends Controller
{
    public function store(UserFriendRequest $request)
    {
        $rec = UserFriend::create([
            'user_id' => Auth::id(),
            'friend_id' => $request->input('friend_id'),
            'accepted' => false,
        ]);

        return $this->created(UserFriendResource::make($rec));
    }
}
