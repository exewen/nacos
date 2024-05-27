<?php

namespace Exewen\Nacos\Services;

use Exewen\Config\Contract\ConfigInterface;
use Exewen\Http\Contract\HttpClientInterface;
use Exewen\Nacos\Exception\HttpDataException;
use Exewen\Utils\FileUtil;

class ConfigService
{
//    private HttpClientInterface $httpClient;
    private $httpClient;
    private $driver;
    private $nacosConfigUrl = '/nacos/v1/cs/configs';

    public function __construct(HttpClientInterface $httpClient, ConfigInterface $config)
    {
        $this->httpClient = $httpClient;
        $this->driver = $config->get('nacos.http_channel');
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
        return $this->httpClient->get($this->driver, $this->nacosConfigUrl, [
            'dataId' => $dataId,
            'group' => $group,
            'tenant' => $namespaceId,
        ]);
    }

    /**
     * 保存本地nacos配置
     * @param string $namespaceId
     * @param string $dataId
     * @param string $group
     * @return string
     */
    public function saveConfig(string $namespaceId, string $dataId, string $group): string
    {
        $dataIdConfig = $this->getConfig($namespaceId, $dataId, $group);

        if (empty($dataIdConfig)) {
            throw new HttpDataException(sprintf("data not find:%s %s %s", $dataId, $group, $namespaceId));
        }

        FileUtil::saveSnapshot($namespaceId, $dataId, $group, $dataIdConfig);
        return $dataIdConfig;
    }


}