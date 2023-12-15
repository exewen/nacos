<?php
declare(strict_types=1);

namespace Exewen\Nacos\Facades\Contract;

interface FacadeInterface
{
    public static function getFacadeAccessor(): string;

    public static function getProviders(): array;
}