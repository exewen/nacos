<?php
declare(strict_types=1);

namespace Exewen\Nacos\Services;

use Exewen\Nacos\Services\Contract\NacosServiceInterface;
use Exewen\Nacos\Services\Logic\ConfigLogic;
use Exewen\Nacos\Services\Logic\InstanceLogic;

class NacosService implements NacosServiceInterface
{
    private ConfigLogic $configLogic;
    private InstanceLogic $instanceLogic;

    public function __construct(ConfigLogic $configLogic, InstanceLogic $instanceLogic)
    {
        $this->configLogic = $configLogic;
        $this->instanceLogic = $instanceLogic;
    }


    public function getConfig(string $namespaceId, string $dataId, string $group): string
    {
        return $this->configLogic->getConfig($namespaceId, $dataId, $group);
    }

    public function saveConfig(string $namespaceId, string $dataId, string $group): string
    {
        return $this->configLogic->saveConfig($namespaceId, $dataId, $group);
    }

    public function getInstance(string $namespaceId, string $serviceName, string $group, bool $healthyOnly = true)
    {
        return $this->instanceLogic->getInstance($namespaceId, $serviceName, $group, $healthyOnly);
    }

    public function setInstance(string $namespaceId, string $serviceName, string $group, string $ip, int $port, $ver = '1.0.0'): bool
    {
        return $this->instanceLogic->setInstance($namespaceId, $serviceName, $group, $ip, $port, $ver);

    }

    public function setInstanceBeat(string $namespaceId, string $serviceName, string $group, string $ip, int $port, $ver = '1.0.0'): bool
    {
        return $this->instanceLogic->setInstanceBeat($namespaceId, $serviceName, $group, $ip, $port, $ver);
    }
}