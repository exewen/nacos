<?php
declare(strict_types=1);

namespace Exewen\Nacos\Facades;

use Exewen\Nacos\Facades\Contract\FacadeInterface;
use Exewen\Nacos\Nacos;

abstract class Facade implements FacadeInterface
{
    protected static $app = null;

    public static function __callStatic($method, $args)
    {
        $facadeApp = static::getFacadeApp();

        $instance = $facadeApp->get(static::getFacadeAccessor());
        if (!$instance) {
            throw new \RuntimeException('A facade root has not been set.');
        }

        return $instance->$method(...$args);
    }

    public static function getFacadeApp(): ?Nacos
    {
        if (self::$app === null) {
            self::$app = new Nacos(static::getProviders());
            NacosLogger::debug("[nacos] 初始化完成");
        }
        return self::$app;
    }


}