<?php
declare(strict_types=1);

namespace Exewen\Nacos\Services;

use Exewen\Nacos\Config\Config;
use Exewen\Nacos\Services\Contract\LoggerServiceInterface;

class LoggerService extends LoggerManager implements LoggerServiceInterface
{
    public function __construct(Config $config)
    {
        parent::__construct($config);
    }

    public function debug($message, array $context = [])
    {
        $this->driver(null, __FUNCTION__)->debug($message, $context);
    }

    public function request($message, array $context = [])
    {
        $this->driver(null, __FUNCTION__)->info($message, $context);
    }

    public function info($message, array $context = [])
    {
        $this->driver(null, __FUNCTION__)->info($message, $context);
    }

    public function notice($message, array $context = [])
    {
        $this->driver(null, __FUNCTION__)->notice($message, $context);
    }

    public function warning($message, array $context = [])
    {
        $this->driver(null, __FUNCTION__)->warning($message, $context);
    }

    public function error($message, array $context = [])
    {
        $this->driver(null, __FUNCTION__)->error($message, $context);
    }

}