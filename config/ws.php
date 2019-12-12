<?php

return [
    'host' => parse_url(env('APP_URL'))['host'] ?? 'localhost',
    'port' => env('WS_PORT'),
    'worker_num' => env('WS_WORKER_NUM'),
    'interval' => env('WS_INTERVAL'),
    'timeout' => env('WS_TIMEOUT'),
];
