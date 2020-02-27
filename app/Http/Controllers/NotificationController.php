<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotificationRequest;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $data = Auth::user()->notifications()->paginate();

        return $this->ok(NotificationResource::collection($data));
    }

    public function update(NotificationRequest $request, Notification $notification)
    {
        if ($request->input('read_at')) {
            $notification->markAsRead();
        }

        return $this->created($notification);
    }
}
