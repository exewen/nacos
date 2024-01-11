<?php

namespace Exewen\Nacos\Services;

use Exewen\Config\Contract\ConfigInterface;
use Exewen\Di\Container;
use Exewen\Http\Contract\HttpClientInterface;
use Exewen\Nacos\Exception\HttpDataException;
use Exewen\Utils\FileUtil;

class ConfigService
{
    private HttpClientInterface $httpClient;
    private string $driver;
    private string $nacosConfigUrl = '/nacos/v1/cs/configs';

    public function __construct(HttpClientInterface $httpClient, ConfigInterface $config)
    {
        $this->httpClient = $httpClient;
        $this->driver = $config->get('nacos.channels');
    }

    public function getConfig(string $namespaceId, string $dataId, string $group): string
    {
        return $this->httpClient->get($this->driver, $this->nacosConfigUrl, [
            'dataId' => $dataId,
            'group' => $group,
            'tenant' => $namespaceId,
        ]);
    }

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