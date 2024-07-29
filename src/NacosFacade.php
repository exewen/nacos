<?php
declare(strict_types=1);

namespace Exewen\Nacos;

use Exewen\Facades\Facade;
use Exewen\Http\HttpProvider;
use Exewen\Logger\LoggerProvider;
use Exewen\Nacos\Contract\NacosInterface;

/**
 * @method static string getConfig(string $namespaceId, string $dataId, string $group)
 * @method static string saveConfig(string $namespaceId, string $dataId, string $group)
 * @method static array getInstance(string $namespaceId, string $serviceName, string $group, bool $healthyOnly = true)
 * @method static bool setInstance(string $namespaceId, string $serviceName, string $group, string $ip, int $port, string $ver = '1.0.0')
 * @method static bool setInstanceBeat(string $namespaceId, string $serviceName, string $group, string $ip, int $port, string $ver = '1.0.0')
 */
class NacosFacade extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return NacosInterface::class;
    }

    public static function getProviders(): array
    {
        return [
            LoggerProvider::class,
            HttpProvider::class,
            NacosProvider::class,
        ];
    }
}