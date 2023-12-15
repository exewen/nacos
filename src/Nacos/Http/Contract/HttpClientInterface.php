<?php
declare(strict_types=1);

namespace Exewen\Nacos\Http\Contract;

interface HttpClientInterface
{
    public function get(string $url, array $params = [], array $header = [], array $options = []): string;

    public function post(string $url, array $params = [], array $header = [], array $options = [], string $type = 'form_params'): string;

    public function put(string $url, array $params = [], array $header = [], array $options = [], string $type = 'form_params'): string;

}