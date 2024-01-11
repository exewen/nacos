<?php

declare(strict_types=1);

namespace Exewen\Nacos;


use Exewen\Nacos\Contract\NacosInterface;
use Exewen\Nacos\Services\ConfigService;
use Exewen\Nacos\Services\InstanceService;

class Nacos implements NacosInterface
{

    private ConfigService $configService;
    private InstanceService $instanceService;

    public function __construct(ConfigService $configService, InstanceService $instanceService)
    {
        $this->configService = $configService;
        $this->instanceService = $instanceService;
    }

    public function getConfig(string $namespaceId, string $dataId, string $group): string
    {
        return $this->configService->getConfig($namespaceId, $dataId, $group);
    }

    public function saveConfig(string $namespaceId, string $dataId, string $group): string
    {
        return $this->configService->saveConfig($namespaceId, $dataId, $group);
    }

    public function getInstance(string $namespaceId, string $serviceName, string $group, bool $healthyOnly = true)
    {
        return $this->instanceService->getInstance($namespaceId, $serviceName, $group, $healthyOnly);
    }

    public function setInstance(string $namespaceId, string $serviceName, string $group, string $ip, int $port, $ver = '1.0.0'): bool
    {
        return $this->instanceService->setInstance($namespaceId, $serviceName, $group, $ip, $port, $ver);
    }

    public function setInstanceBeat(string $namespaceId, string $serviceName, string $group, string $ip, int $port, $ver = '1.0.0'): bool
    {
        return $this->instanceService->setInstanceBeat($namespaceId, $serviceName, $group, $ip, $port, $ver);
    }

}
