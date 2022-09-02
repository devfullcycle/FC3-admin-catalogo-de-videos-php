<?php

return [
    'queue_name' => env('micro_catalog'),
    
    'rabbitmq' => [
        'hosts' => [
            [
                'host' => env('RABBITMQ_HOST', '127.0.0.1'),
                'port' => env('RABBITMQ_PORT', 5672),
                'user' => env('RABBITMQ_USER', 'guest'),
                'password' => env('RABBITMQ_PASSWORD', 'guest'),
                'vhost' => env('RABBITMQ_VHOST', '/'),
            ],
        ],
    ],

    'micro_encoder_go' => [
        'exchange' => 'dlx',
        'queue_name' => 'video'
    ]
];
