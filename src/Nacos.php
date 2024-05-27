<?php

declare(strict_types=1);

namespace Exewen\Nacos;


use Exewen\Nacos\Contract\NacosInterface;
use Exewen\Nacos\Services\ConfigService;
use Exewen\Nacos\Services\InstanceService;

class Nacos implements NacosInterface
{

//    private ConfigService $configService;
    private $configService;
//    private InstanceService $instanceService;
    private $instanceService;

    public function __construct(ConfigService $configService, InstanceService $instanceService)
    {
        $this->configService = $configService;
        $this->instanceService = $instanceService;
    }

    /**
     * 获取nacos配置
     * @param string $namespaceId
     * @param string $dataId
     * @param string $group
     * @return string
     */
    public function getConfig(string $namespaceId, string $dataId, string $group): string
    {
        return $this->configService->getConfig($namespaceId, $dataId, $group);
    }

    /**
     * 保存配置文件到本地
     * @param string $namespaceId
     * @param string $dataId
     * @param string $group
     * @return string
     */
    public function saveConfig(string $namespaceId, string $dataId, string $group): string
    {
        return $this->configService->saveConfig($namespaceId, $dataId, $group);
    }

    /**
     * 获取服务实例列表
     * @param string $namespaceId
     * @param string $serviceName
     * @param string $group
     * @param bool $healthyOnly
     * @return mixed
     */
    public function getInstance(string $namespaceId, string $serviceName, string $group, bool $healthyOnly = true)
    {
        return $this->instanceService->getInstance($namespaceId, $serviceName, $group, $healthyOnly);
    }

    /**
     * 注册服务实例
     * @param string $namespaceId
     * @param string $serviceName
     * @param string $group
     * @param string $ip
     * @param int $port
     * @param $ver
     * @return bool
     */
    public function setInstance(string $namespaceId, string $serviceName, string $group, string $ip, int $port, $ver = '1.0.0'): bool
    {
        return $this->instanceService->setInstance($namespaceId, $serviceName, $group, $ip, $port, $ver);
    }

    /**
     * 发送服务心跳
     * @param string $namespaceId
     * @param string $serviceName
     * @param string $group
     * @param string $ip
     * @param int $port
     * @param $ver
     * @return bool
     */
    public function setInstanceBeat(string $namespaceId, string $serviceName, string $group, string $ip, int $port, $ver = '1.0.0'): bool
    {
        return $this->instanceService->setInstanceBeat($namespaceId, $serviceName, $group, $ip, $port, $ver);
    }

}
