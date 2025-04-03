<?php

return [
    'host' => env('AMI_HOST', '127.0.0.1'),
    'port' => env('AMI_PORT', 5038),
    'username' => env('AMI_USERNAME', 'admin'),
    'secret' => env('AMI_SECRET', 'yourpassword'),
    'connect_timeout' => env('AMI_CONNECT_TIMEOUT', 10),
    'read_timeout' => env('AMI_READ_TIMEOUT', 10),
];
