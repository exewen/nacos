<?php
declare(strict_types=1);

namespace Exewen\Nacos;

use Exewen\Di\ServiceProvider;
use Exewen\Nacos\Contract\NacosInterface;

class NacosProvider extends ServiceProvider
{

    public function register()
    {
        $this->container->singleton(NacosInterface::class);
    }

}