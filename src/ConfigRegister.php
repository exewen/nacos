<?php

declare(strict_types=1);

namespace Exewen\Nacos;

use Exewen\Nacos\Contract\NacosInterface;

class ConfigRegister
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                NacosInterface::class => Nacos::class,
            ],

            'nacos' => [
                // 选择http模块驱动
                'http_channel' => 'nacos'
            ]


        ];
    }
}
