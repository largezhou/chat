<?php

namespace App\Http\Controllers;

use App\Admin\Traits\RestfulResponse;
use App\Http\Filters\UserFilter;
use App\Http\Requests\RecentContactRequest;
use App\Http\Resources\MsgResource;
use App\Http\Resources\RecentContactResource;
use App\Http\Resources\UserResource;
use App\Models\Msg;
use App\Models\RecentContact;
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

    public function getRecentContacts(RecentContact $contact)
    {
        $contacts = RecentContact::getContactsBy(Auth::id());

        return $this->ok(RecentContactResource::collection($contacts));
    }

    public function storeRecentContacts(RecentContactRequest $request)
    {
        $contact = RecentContact::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'target_id' => $request->input('target_id'),
            ]
        )->load(['target']);
        return $this->ok(RecentContactResource::make($contact));
    }

    public function index(UserFilter $filter)
    {
        $userId = Auth::id();

        $users = User::query()
            ->where('id', '<>', $userId)
            ->isNotFriend($userId)
            ->filter($filter->only(['q']))
            ->get();

        return $this->ok(UserResource::collection($users));
    }
}
