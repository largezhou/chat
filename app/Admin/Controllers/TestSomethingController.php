<?php

namespace App\Admin\Controllers;

use App\Admin\Models\Config;
use App\Models\User;
use App\Models\UserFriend;

class TestSomethingController extends Controller
{
    public function index($path = null)
    {
        dd(UserFriend::query()->with(['inviter', 'invitee'])->get()->toArray());
    }
}
