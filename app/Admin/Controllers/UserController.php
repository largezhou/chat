<?php

namespace App\Admin\Controllers;

use App\Admin\Filters\UserFilter;
use App\Admin\Requests\UserRequest;
use App\Admin\Resources\UserFriendResource;
use App\Admin\Resources\UserResource;
use App\Models\User;
use App\Models\UserFriend;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(UserRequest $request)
    {
        $user = User::createUser($request->validated());
        return $this->ok(UserResource::make($user));
    }

    public function index(Request $request, UserFilter $filter)
    {
        $users = User::query()
            ->filter($filter)
            ->orderByDesc('id')
            ->paginate();

        return $this->ok(UserResource::collection($users));
    }

    public function update(UserRequest $request, User $user)
    {
        $user->updateUser($request->validated());
        return $this->created(UserResource::make($user));
    }

    public function edit(User $user)
    {
        return $this->ok(UserResource::make($user));
    }

    public function destroy(User $user)
    {
        $user->delete();
        return $this->noContent();
    }

    public function friendships(Request $request)
    {
        $userFriends = UserFriend::query()
            ->with(['inviter', 'invitee'])
            ->orderBy('created_at')
            ->paginate();

        return $this->ok(UserFriendResource::collection($userFriends));
    }
}
