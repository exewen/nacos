<?php

namespace Exewen\Nacos\Http\Middleware;

use Exewen\Nacos\Config\Config;
use Psr\Http\Message\RequestInterface;

class HeaderMiddleware
{
    private Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function __invoke(callable $handler): callable
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            $identityKey = $this->config->get('nacos.identity_key');
            $identityValue = $this->config->get('nacos.identity_value');
            $modifiedRequest = $request->withHeader($identityKey, $identityValue);
            return $handler($modifiedRequest, $options);
        };
    }


}