<?php

namespace App\Http\Resources;

use App\Admin\Resources\JsonResource;
use App\Models\Notification;
use App\Models\User;

class NotificationResource extends JsonResource
{
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['type'] = Notification::formatType($data['type']);

        $data = $this->handleFriendRequestedData($data);

        return $data;
    }

    /**
     * 获取申请人的姓名
     * TODO 暂时这样 N + 1 做法，之后用统一的方式，做类似 with 的预加载
     *
     * @param array $data
     *
     * @return array
     */
    protected function handleFriendRequestedData(array $data): array
    {
        $user = User::find($data['data']['inviter_id']);

        $data['data']['inviter_name'] = optional($user)->name;

        return $data;
    }
}
