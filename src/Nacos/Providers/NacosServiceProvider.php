<?php
declare(strict_types=1);

namespace Exewen\Nacos\Providers;

use Exewen\Nacos\Services\Contract\NacosServiceInterface;

class NacosServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->make(NacosServiceInterface::class);
    }
}