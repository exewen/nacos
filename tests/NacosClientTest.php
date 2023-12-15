<?php
declare(strict_types=1);

namespace Exewen\Test;

use Exewen\Nacos\Facades\NacosClient;
use Exewen\Nacos\Util\FileUtil;

class NacosClientTest extends BaseTest
{
    private string $namespaceId = "test";
    private string $dataId = "pms-user.env";
    private string $group = "DEFAULT_GROUP";
    private string $serverName = "test-user";
    private string $ip = "20.18.7.10";
    private int $port = 8080;

    /**
     * 生成配置
     * @return void
     */
    public function testSaveConfig()
    {
        $str = NacosClient::getConfig($this->namespaceId, $this->dataId, $this->group);
        $this->assertNotEmpty($str);
    }

    /**
     * 获取配置
     * @return void
     */
    public function testGetConfig()
    {
        $path = FileUtil::getSnapshotFile($this->namespaceId, $this->dataId, $this->group);
        $this->assertNotEmpty($path);
    }

    /**
     * 注册实例
     * @return void
     */
    public function testSetInstance()
    {
        $result = NacosClient::setInstance($this->namespaceId, $this->serverName, $this->group, $this->ip, $this->port);
        $this->assertTrue($result);
    }

    /**
     * 实例心跳
     * @return void
     */
    public function testSetInstanceBeat()
    {
        $result = NacosClient::setInstanceBeat($this->namespaceId, $this->serverName, $this->group, $this->ip, $this->port);
        $this->assertTrue($result);

    }

    /**
     * 获取实例
     * @return void
     */
    public function testGetInstance()
    {
        $result = NacosClient::getInstance($this->namespaceId, $this->serverName, $this->group, true);
        $this->assertNotEmpty($result['hosts'] ?? []);
    }


}