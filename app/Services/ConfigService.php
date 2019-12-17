<?php

namespace App\Services;

class ConfigService
{
    protected static $basicKeyAliasMap = [
        'ws.port',
        'filesystems.disks.uploads.url' => 'cdn_domain',
    ];

    /**
     * 应用最基本的配置，需要给前端
     *
     * @return array
     */
    public static function basic()
    {
        $t = [];
        foreach (static::$basicKeyAliasMap as $key => $alias) {
            if (is_numeric($key)) {
                $key = $alias;
                $alias = str_replace('.', '_', $alias);
            }
            $t[$alias] = config($key);
        }

        return $t;
    }
}
