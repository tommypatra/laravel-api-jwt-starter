<?php
return [
    'base_url' => env('SEVIMA_BASE_URL'),
    'headers' => [
        'X-App-Key' => env('SEVIMA_APP_KEY'),
        'X-Secret-Key' => env('SEVIMA_SECRET_KEY'),
        'Accept' => 'application/json',
    ],
    'timeout' => env('SEVIMA_TIMEOUT', 30),
];