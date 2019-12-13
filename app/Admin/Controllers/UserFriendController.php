<?php

namespace App\Admin\Controllers;

use App\Admin\Filters\UserFriendFilter;
use App\Admin\Resources\UserFriendResource;
use App\Models\UserFriend;
use Illuminate\Http\Request;

class UserFriendController extends Controller
{
    public function index(UserFriendFilter $filter)
    {
        $userFriends = UserFriend::query()
            ->with(['inviter', 'invitee'])
            ->filter($filter)
            ->orderBy('created_at')
            ->paginate();

        return $this->ok(UserFriendResource::collection($userFriends));
    }

    public function destroy(Request $request, UserFriend $userFriend)
    {
        $userFriend->delete();
        return $this->noContent();
    }
}
