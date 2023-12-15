<?php

declare(strict_types=1);

namespace Exewen\Nacos\Config;

use Exewen\Nacos\Config\Contract\ConfigInterface;
use Symfony\Component\Finder\Finder;

class ConfigFactory
{
    public function __invoke(): ConfigInterface
    {
        $configFile = $this->readPath([BASE_NACOS_PATH . '/Nacos/Config/Publish']);
        $autoloadConfig = $this->readPath([BASE_NACOS_PATH . '/../config/nacos']);
        $config = array_replace_recursive($configFile, $autoloadConfig);
        return new Config($config);
    }

    protected function readConfig(string $string): array
    {
        if (!is_file($string)) {
            return [];
        }

        $config = require $string;
        if (!is_array($config)) {
            return [];
        }
        return $config;
    }

    protected function readPath(array $dirs): array
    {
        $config = [];
        $finder = new Finder();
        $finder->files()->in($dirs)->name('*.php');
        foreach ($finder as $fileInfo) {
            $key = $fileInfo->getBasename('.php');
            $value = require $fileInfo->getRealPath();
            $config[$key] = $value;
        }
        return $config;
    }


}