<?php

declare(strict_types=1);

return [
    'channels' => [
        'nacos_api' => [
            'verify'          => false,
            'ssl'             => false,
            'host'            => 'xxx.com',
            'port'            => null,
            'prefix'          => null,
            'connect_timeout' => 3,
            'timeout'         => 20,
            'handler'         => [],
            'extra'           => [],
            'proxy'           => [
                'switch' => false,
                'http'   => '127.0.0.1:8888',
                'https'  => '127.0.0.1:8888'
            ]
        ],
    ]
];