<?php

return [
    'host' => parse_url(env('APP_URL'))['host'] ?? 'localhost',
    'port' => env('WS_PORT', 9501),
    'worker_num' => env('WS_WORKER_NUM', 4),
];
