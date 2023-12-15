<?php

use Monolog\Formatter\LineFormatter;
use Monolog\Formatter\NormalizerFormatter;

return [
    'default' => 'info',
    // driver: daily/single
    'channels' => [
        'info' => [
            'driver' => 'daily',
            'path' => BASE_NACOS_PATH . '/../logs/nacos-info.log',
            'days' => 30,
            'level' => 'debug',

            'enable' => false,
        ],
        'error' => [
            'driver' => 'daily',
            'path' => BASE_NACOS_PATH . '/../logs/nacos-error.log',
            'days' => 30,
            'level' => 'error',
        ],
        'request' => [
            'driver' => 'single',
            'path' => BASE_NACOS_PATH . '/../logs/nacos-request.log',
            'days' => 30,
            'level' => 'debug',
            'formatter' => [
                'constructor' => [
                    'format' => LineFormatter::SIMPLE_FORMAT,
                    'dateFormat' => NormalizerFormatter::SIMPLE_DATE,
                ],
            ],
        ],
        'debug' => [
            'driver' => 'single',
            'path' => BASE_NACOS_PATH . '/../logs/nacos-debug.log',
            'days' => 30,
            'level' => 'debug',
            'formatter' => [
                'constructor' => [
                    'format' => LineFormatter::SIMPLE_FORMAT,
                    'dateFormat' => NormalizerFormatter::SIMPLE_DATE,
                ],
            ],
        ]
    ]

];