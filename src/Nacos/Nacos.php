<?php
declare(strict_types=1);

namespace Exewen\Nacos;

use Exewen\Nacos\Config\Config;
use Exewen\Nacos\Config\ConfigFactory;
use Exewen\Nacos\Providers\NacosServiceProvider;
use Exewen\Nacos\Providers\ServiceProvider;

class Nacos
{
    /**
     * The current globally available container
     * @var Nacos
     */
    protected static Nacos $instance;
    /**
     * The container's shared instances.
     * @var array
     */
    protected array $instances = [];
    /**
     * providers
     * @var array
     */
    protected array $providers = [];

    /**
     * dependencies
     * @var array|string[]
     */
    protected array $dependencies = [
        \Exewen\Nacos\Services\Contract\NacosServiceInterface::class => \Exewen\Nacos\Services\NacosService::class,
        \Exewen\Nacos\Services\Contract\LoggerServiceInterface::class => \Exewen\Nacos\Services\LoggerService::class
    ];

    public function __construct($providers = [NacosServiceProvider::class])
    {
        !defined('BASE_NACOS_PATH') && define('BASE_NACOS_PATH', dirname(__DIR__, 1));
        $this->instance(Config::class, (new ConfigFactory())());
        $this->providers = $providers;
        $this->registerProviders();
        self::$instance = $this;
    }

    /**
     * register instance
     * @param $abstract
     * @param $instance
     * @return mixed
     */
    public function instance($abstract, $instance)
    {
        $this->instances[$abstract] = $instance;
        return $this->instances[$abstract];
    }

    /**
     * 服务提供者
     * @return void
     */
    private function registerProviders()
    {
        foreach ($this->providers as $provider) {
            $prov = new $provider($this);

            if ($prov instanceof ServiceProvider) {
                $prov->register();
            }
        }
    }

    /**
     * static get instance
     * @return Nacos
     */
    public static function getInstance(): Nacos
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * get instance
     * @param $abstract
     * @return mixed|object|null
     */
    public function get($abstract)
    {
        $abstract = $this->convertDependencies($abstract, true);
        if (!isset($this->instances[$abstract])) {
            return $this->make($abstract);
        }
        return $this->instances[$abstract];
    }

    /**
     * 转换类绑定
     * @param $abstract
     * @param bool $flip
     * @return int|mixed|string
     */
    private function convertDependencies($abstract, bool $flip = false)
    {
        if ($flip) {
            return array_flip($this->dependencies)[$abstract] ?? $abstract;
        }
        return $this->dependencies[$abstract] ?? $abstract;
    }

    /**
     * make instance
     * @param $abstract
     * @return mixed|object|null
     */
    public function make($abstract)
    {
        $this->instances[$abstract] = $object = $this->getMakeInstance($abstract);
        return $object;
    }

    /**
     * 获取实例
     * @param $abstract
     * @return int|mixed|object|null
     */
    private function getMakeInstance($abstract)
    {
        // 过滤返回全局单例
        if (isset($this->instances[$abstract]) && $this->filterSingleton($abstract)) {
            return $this->instances[$abstract];
        }
        // 接口类映射
        $abstract = $this->convertDependencies($abstract);

        $reflector = new \ReflectionClass($abstract);
        // 获取构造方法
        $constructor = $reflector->getConstructor();
        if (!$constructor) {
            return new $abstract();
        }
        // 获取构造方法参数
        $dependencies = $constructor->getParameters();
        if (!$dependencies) {
            return new $abstract();
        }

        // 依赖注入
        $p = [];
        foreach ($dependencies as $dependency) {
            if (!is_null($dependency->getClass())) {
                $p[] = $this->make($dependency->getClass()->name);
            }
        }
        return $reflector->newInstanceArgs($p);
    }

    /**
     * 过滤单例
     * @param $abstract
     * @return bool
     */
    private function filterSingleton($abstract): bool
    {
        return $abstract == Config::class;
    }


}