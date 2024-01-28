<?php
declare(strict_types=1);

namespace Exewen\Nacos;

use Exewen\Di\ServiceProvider;
use Exewen\Nacos\Contract\NacosInterface;

class NacosProvider extends ServiceProvider
{

    /**
     * 服务注册
     * @return void
     */
    public function register()
    {
        $this->container->singleton(NacosInterface::class);
    }

}