<?php

namespace App\ChatServer;

class Data
{
    public static function encode(string $type, $data = null): string
    {
        $t = [
            'type' => $type,
        ];
        if ($data !== null) {
            $t['data'] = $data;
        }
        return json_encode($t);
    }

    public static function decode(string $data): array
    {
        $res = json_decode($data, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $res = [];
        }

        return [
            'type' => $res['type'] ?? '',
            'data' => $res['data'] ?? null,
        ];
    }
}
