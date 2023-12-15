<?php

namespace Exewen\Nacos\Services\Logic;

use Exewen\Nacos\Exception\DataException;
use Exewen\Nacos\Http\HttpClient;
use Exewen\Nacos\Nacos;
use Exewen\Nacos\Util\FileUtil;

class ConfigLogic
{

    private HttpClient $httpClient;

    private string $nacosConfigUrl = '/nacos/v1/cs/configs';

    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getConfig(string $namespaceId, string $dataId, string $group): string
    {
        return $this->httpClient->get($this->nacosConfigUrl, [
            'dataId' => $dataId,
            'group' => $group,
            'tenant' => $namespaceId,
        ]);
    }

    public function saveConfig(string $namespaceId, string $dataId, string $group): string
    {
        $dataIdConfig = $this->httpClient->get($this->nacosConfigUrl, [
            'dataId' => $dataId,
            'group' => $group,
            'tenant' => $namespaceId,
        ]);

        if (empty($dataIdConfig)) {
            throw new DataException(sprintf("data not find:%s %s %s", $dataId, $group, $namespaceId));
        }

        $app = Nacos::getInstance();
        /** @var FileUtil $fileUtil */
        $fileUtil = $app->get(FileUtil::class);
        $fileUtil->saveSnapshot($namespaceId, $dataId, $group, $dataIdConfig);

        return $dataIdConfig;
    }


}