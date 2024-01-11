<?php
declare(strict_types=1);

namespace ExewenTest\Nacos;

use Exewen\Di\Container;
use Exewen\Nacos\Contract\NacosInterface;
use PHPUnit\Framework\TestCase;

class NacosTest extends TestCase
{
    public function __construct()
    {
        parent::__construct();
        !defined('BASE_PATH_PKG') && define('BASE_PATH_PKG', dirname(__DIR__, 1));
        $projectPath = dirname(__DIR__);
        \Exewen\Utils\FileUtil::setSnapshotPath($projectPath . "/config/nacos/env");
    }

    public function testSaveConfig()
    {
        $app = new Container();
        /** @var NacosInterface $nacos */
        $nacos = $app->get(NacosInterface::class);
        $namespaceId = getenv('NACOS_ENV_PMS_USER');
        $serviceName = getenv('NACOS_DATA_ID_PMS_USER');
        $group = getenv('NACOS_GROUP_PMS_USER');
        $config = $nacos->saveConfig($namespaceId, $serviceName, $group);
        $this->assertNotEmpty($config);
    }

    public function testGetLog()
    {
        $namespaceId = getenv('NACOS_ENV_PMS_USER');
        $dataId = getenv('NACOS_DATA_ID_PMS_USER');
        $group = getenv('NACOS_GROUP_PMS_USER');
        $tempPath = \Exewen\Utils\FileUtil::getSnapshotFile($namespaceId, $dataId, $group);
        $envPath = substr($tempPath, strlen(BASE_PATH_PKG) + 1);
        !is_file($tempPath) && $envPath = '.env';
        $this->assertTrue($envPath !== '.env');
    }


    /**
     * 注册实例
     * @return void
     */
    public function testSetInstance()
    {
        $app = new Container();
        /** @var NacosInterface $nacos */
        $nacos = $app->get(NacosInterface::class);
        $namespaceId = getenv('NACOS_ENV_PMS_USER');
        $serviceName = getenv('NACOS_DATA_ID_PMS_USER');
        $group = getenv('NACOS_GROUP_PMS_USER');
        $ip = getenv('NACOS_IP_PMS_USER');
        $port = intval(getenv('NACOS_PORT_PMS_USER'));
        $result = $nacos->setInstance($namespaceId, $serviceName, $group, $ip, $port);
        $this->assertTrue($result);
    }

    /**
     * 实例心跳
     * @return void
     */
    public function testSetInstanceBeat()
    {
        $app = new Container();
        /** @var NacosInterface $nacos */
        $nacos = $app->get(NacosInterface::class);
        $namespaceId = getenv('NACOS_ENV_PMS_USER');
        $serviceName = getenv('NACOS_DATA_ID_PMS_USER');
        $group = getenv('NACOS_GROUP_PMS_USER');
        $ip = getenv('NACOS_IP_PMS_USER');
        $port = intval(getenv('NACOS_PORT_PMS_USER'));
        $result = $nacos->setInstanceBeat($namespaceId, $serviceName, $group, $ip, $port);
        $this->assertTrue($result);

    }

    /**
     * 获取实例
     * @return void
     */
    public function testGetInstance()
    {
        $app = new Container();
        /** @var NacosInterface $nacos */
        $nacos = $app->get(NacosInterface::class);
        $namespaceId = getenv('NACOS_ENV_PMS_USER');
        $serviceName = getenv('NACOS_DATA_ID_PMS_USER');
        $group = getenv('NACOS_GROUP_PMS_USER');
        $result = $nacos->getInstance($namespaceId, $serviceName, $group, true);
        $this->assertNotEmpty($result['hosts'] ?? []);
    }

}