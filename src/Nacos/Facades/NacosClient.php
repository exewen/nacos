<?php
declare(strict_types=1);

namespace Exewen\Nacos\Facades;

use Exewen\Nacos\Providers\NacosServiceProvider;
use Exewen\Nacos\Services\Contract\NacosServiceInterface;

/**
 * @method static string getConfig(string $namespaceId, string $dataId, string $group)
 * @method static string saveConfig(string $namespaceId, string $dataId, string $group)
 * @method static array getInstance(string $namespaceId, string $dataId, string $group, bool $healthyOnly = true)
 * @method static bool setInstance(string $namespaceId, string $dataId, string $group, string $ip, int $port, string $ver = '1.0.0')
 * @method static bool setInstanceBeat(string $namespaceId, string $dataId, string $group, string $ip, int $port, string $ver = '1.0.0')
 */
class NacosClient extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return NacosServiceInterface::class;
    }

    public static function getProviders(): array
    {
        return [NacosServiceProvider::class];
    }
}