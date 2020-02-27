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

    public function update(UserFriendRequest $request, UserFriend $userFriend)
    {
        if ($request->input('accepted') && !$userFriend->accept()) {
            return $this->error('你们已经是好友了。');
        }

        return $this->created(UserFriendResource::make($userFriend));
    }
}
