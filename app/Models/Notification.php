<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Notification extends Model
{
    /**
     * 简化一下消息的 type 值
     *
     * @param string $type
     *
     * @return string
     */
    public static function formatType(string $type): string
    {
        return Str::kebab(class_basename($type));
    }
}
