<?php
declare(strict_types=1);

namespace Exewen\Nacos\Providers;

use Exewen\Nacos\Config\Config;
use Exewen\Nacos\Services\LoggerService;

class LoggerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->instance('log', function () {
            return new LoggerService($this->app->get(Config::class));
        });
    }
}