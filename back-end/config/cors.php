<?php

return [
    'paths' => ['*'], // cho tất cả các path
    'allowed_methods' => ['*'], // tất cả GET, POST, PUT, DELETE...
    'allowed_origins' => ['http://localhost:3000'], // origin frontend của bạn
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'], // cho phép tất cả header
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false, // nếu không dùng cookie
];
