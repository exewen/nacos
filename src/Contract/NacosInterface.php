<?php
declare(strict_types=1);

namespace Exewen\Nacos\Contract;


interface NacosInterface
{
    /**
     * 获取配置文件路径
     * @param string $namespaceId
     * @param string $dataId
     * @param string $group
     * @return mixed
     */
    public function getConfig(string $namespaceId, string $dataId, string $group);

    /**
     * 保存配置文件
     * @param string $namespaceId
     * @param string $dataId
     * @param string $group
     * @return mixed
     */
    public function saveConfig(string $namespaceId, string $dataId, string $group);

    /**
     * 获取服务实例列表
     * @param string $namespaceId
     * @param string $serviceName
     * @param string $group
     * @param bool $healthyOnly
     * @return mixed
     */
    public function getInstance(string $namespaceId, string $serviceName, string $group, bool $healthyOnly = true);

    /**
     * 注册服务实例
     * @param string $namespaceId
     * @param string $serviceName
     * @param string $group
     * @param string $ip
     * @param int $port
     * @param string $ver
     * @return mixed
     */
    public function setInstance(string $namespaceId, string $serviceName, string $group, string $ip, int $port, string $ver);

    /**
     * 发送服务心跳
     * @param string $namespaceId
     * @param string $serviceName
     * @param string $group
     * @param string $ip
     * @param int $port
     * @param string $ver
     * @return mixed
     */
    public function setInstanceBeat(string $namespaceId, string $serviceName, string $group, string $ip, int $port, string $ver);

}