<?php
declare(strict_types=1);

namespace Exewen\Nacos\Providers;

use Exewen\Nacos\Nacos;

abstract class ServiceProvider
{
    protected Nacos $app;

    public function __construct(Nacos $app)
    {
        $this->app = $app;
    }

    public function register()
    {
        //
    }
}