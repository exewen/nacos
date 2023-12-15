<?php
declare(strict_types=1);

namespace Exewen\Nacos\Services;

use Exewen\Nacos\Config\Config;
use Exewen\Nacos\Exception\InvalidArgumentException;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\FormattableHandlerInterface;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as Monolog;
use Psr\Log\LoggerInterface;

class LoggerManager
{
    /**
     * The array of resolved channels.
     *
     * @var array
     */
    protected array $channels = [];
    private Config $config;
    protected array $levels = [
        'debug' => Monolog::DEBUG,
        'info' => Monolog::INFO,
        'notice' => Monolog::NOTICE,
        'warning' => Monolog::WARNING,
        'error' => Monolog::ERROR,
        'critical' => Monolog::CRITICAL,
        'alert' => Monolog::ALERT,
        'emergency' => Monolog::EMERGENCY,
    ];
    protected $dateFormat = 'Y-m-d H:i:s';

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    protected function driver($driver = null, $function = null): LoggerInterface
    {
        if (is_null($driver) && !is_null($function)) {
            $driver = $function;
        }
        return $this->get($driver ?? 'info');
    }

    protected function get($name): LoggerInterface
    {
        return $this->channels[$name] ?? $this->resolve($name);
    }

    /**
     * 注册
     * @param $name
     * @return LoggerInterface
     */
    protected function resolve($name): LoggerInterface
    {
        $config = $this->configurationFor($name);

        if (is_null($config)) {
            throw new InvalidArgumentException("Log [{$name}] is not defined.");
        }
        $driverMethod = 'create' . ucfirst($config['driver']) . 'Driver';

        if (method_exists($this, $driverMethod)) {
            return $this->channels[$name] = $this->{$driverMethod}($config);
        }

        throw new InvalidArgumentException("Driver [{$config['driver']}] is not supported.");
    }

    /**
     * 获取当前日志配置
     * @param $name
     * @return array|mixed|null
     */
    protected function configurationFor(&$name)
    {
        $find = $this->config->get("logging.channels.{$name}");
        if (is_null($find)) {
            // 默认驱动替换
            $name = $this->config->get("logging.default");
        }
        return $this->config->get("logging.channels.{$name}");
    }


    /**
     * Create an instance of the daily file log driver.
     *
     * @param array $config
     * @return LoggerInterface
     */
    protected function createDailyDriver(array $config)
    {
        return new Monolog($this->parseChannel($config), [
            $this->prepareHandler(new RotatingFileHandler(
                $config['path'],
                $config['days'] ?? 30,
                $this->level($config),
                $config['bubble'] ?? true,
                $config['permission'] ?? null,
                $config['locking'] ?? false
            ), $config),
        ]);
    }

    /**
     * Create an instance of the single file log driver.
     *
     * @param array $config
     * @return LoggerInterface
     */
    protected function createSingleDriver(array $config)
    {
        return new Monolog($this->parseChannel($config), [
            $this->prepareHandler(
                new StreamHandler(
                    $config['path'],
                    $this->level($config),
                    $config['bubble'] ?? true,
                    $config['permission'] ?? null,
                    $config['locking'] ?? false
                ), $config
            ),
        ]);
    }

    protected function prepareHandler(HandlerInterface $handler, array $config = []): HandlerInterface
    {
        $isHandlerFormatter = false;

        if (Monolog::API === 1) {
            $isHandlerFormatter = true;
        } elseif (Monolog::API === 2 && $handler instanceof FormattableHandlerInterface) {
            $isHandlerFormatter = true;
        }

        if ($isHandlerFormatter) {
            $handler->setFormatter($this->formatter($config['formatter'] ?? []));
        }

        return $handler;
    }

    private function formatter(array $config): LineFormatter
    {
        return new LineFormatter(
            $config['constructor']['format'] ?? null,
            $config['constructor']['dateFormat'] ?? $this->dateFormat,
            false,
            true
        );
    }

    protected function level(array $config): int
    {
        $level = $config['level'] ?? 'debug';

        if (isset($this->levels[$level])) {
            return $this->levels[$level];
        }
        throw new InvalidArgumentException('Invalid log level.');
    }

    protected function parseChannel(array $config)
    {
        return $config['name'] ?? $this->getFallbackChannelName();
    }

    private function getFallbackChannelName():string
    {
        return $this->config->get('app.app_env');
    }

}