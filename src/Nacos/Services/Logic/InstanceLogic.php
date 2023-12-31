<?php

namespace Exewen\Nacos\Services\Logic;

use Exewen\Nacos\Http\HttpClient;

class InstanceLogic
{

    private HttpClient $httpClient;

    private string $nacosInstanceUrl = '/nacos/v1/ns/instance';
    private string $nacosInstanceListUrl = '/nacos/v1/ns/instance/list';
    private string $nacosInstanceBeatUrl = '/nacos/v1/ns/instance/beat';

    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }


    public function getInstance(string $namespaceId, string $serviceName, string $group, bool $healthyOnly)
    {
        $result = $this->httpClient->get($this->nacosInstanceListUrl, [
            'serviceName' => $serviceName,
            'groupName' => $group,
            'namespaceId' => $namespaceId,
            'healthyOnly' => $healthyOnly,
        ]);
        return json_decode($result, true);
    }

    public function setInstance(string $namespaceId, string $serviceName, string $group, string $ip, int $port, string $ver): bool
    {
        $result = $this->httpClient->post($this->nacosInstanceUrl, [
            'namespaceId' => $namespaceId,
            'serviceName' => $serviceName,
            'groupName' => $group,
            'ip' => $ip,
            'port' => $port,
            'metadata' => json_encode([
                'ver' => $ver
            ], JSON_UNESCAPED_UNICODE),
        ]);
        return $result == "ok";
    }

    public function setInstanceBeat(string $namespaceId, string $serviceName, string $group, string $ip, int $port, string $ver): bool
    {
        $cluster = 'DEFAULT';
        $beatServiceName = implode('@@', [$group, $serviceName]);
        $instanceId = implode('#', [$ip, $port, $cluster, $beatServiceName]);

        $result = $this->httpClient->put($this->nacosInstanceBeatUrl, [
            'namespaceId' => $namespaceId,
            'serviceName' => $serviceName,
            'groupName' => $group,
            'ip' => $ip,
            'port' => $port,
            'beat' => json_encode([
                'cluster' => $cluster,
                'ip' => $ip,
                'metadata' => ['ver' => $ver],
                'port' => $port,
                'scheduled' => true,
                'serviceName' => $beatServiceName,
                'instanceId' => $instanceId,
            ], JSON_UNESCAPED_UNICODE),
        ]);
        $data = json_decode($result);
        if (isset($data->code) && $data->code == 10200) {
            return true;
        } else {
            return false;
        }
    }
}