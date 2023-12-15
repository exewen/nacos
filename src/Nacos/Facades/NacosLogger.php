<?php
declare(strict_types=1);

namespace Exewen\Nacos\Facades;

use Exewen\Nacos\Providers\LoggerServiceProvider;
use Exewen\Nacos\Services\Contract\LoggerServiceInterface;

/**
 * @method static void info(string $message, array $context = [])
 * @method static void error(string $message, array $context = [])
 * @method static void warning(string $message, array $context = [])
 * @method static void notice(string $message, array $context = [])
 * @method static void debug(string $message, array $context = [])
 * @method static void request(string $message, array $context = [])
 */
class NacosLogger extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return LoggerServiceInterface::class;
    }

    public static function getProviders(): array
    {
        return [LoggerServiceProvider::class];
    }
}