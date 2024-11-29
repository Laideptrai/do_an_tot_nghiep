<?php

return [
    'client_id' => env('AeZJVkmmia40IIoSXX5GCee62THTSybbLnhtJRqIpvFoe4r-f2qjxBwW_NImvbM45clpE0gzIg7cimQi'),
    'secret' => env('EP_9F67bNtEM78Ii0wLvLFxeR6ErnEbYkVYtXNFmX0uNtL0zsboY6fX1eFGH3qMbDzudX3hk_U3hN575'),
    'settings' => [
        'mode' => env('PAYPAL_MODE', 'sandbox'), // or 'live'
        'http.ConnectionTimeOut' => 30,
        'log.LogEnabled' => true,
        'log.FileName' => storage_path('logs/paypal.log'),
        'log.LogLevel' => 'DEBUG',
    ],
];
