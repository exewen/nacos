<?php
declare(strict_types=1);

namespace Exewen\Nacos\Http;

use Exewen\Nacos\Config\Config;
use Exewen\Nacos\Exception\HttpClientException;
use Exewen\Nacos\Http\Contract\HttpClientInterface;
use Exewen\Nacos\Http\Middleware\HeaderMiddleware;
use Exewen\Nacos\Http\Middleware\LogMiddleware;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use Psr\Http\Message\ResponseInterface;

class HttpClient implements HttpClientInterface
{
    protected Client $client;
    private array $httpRequestBaseOptions;


    public function __construct(Config $config)
    {
        $this->httpRequestBaseOptions = [
            'connect_timeout' => $config->get('nacos.connect_timeout'),
            'timeout' => $config->get('nacos.timeout'), //请求超时时间
            'verify' => false,
            'debug' => false,
        ];


        $stack = new HandlerStack();
        $stack->setHandler(new CurlHandler());
        $stack->push(new HeaderMiddleware($config),'header');
        $stack->push(new LogMiddleware(),'log');

        $this->client = new Client([
            'handler' => $stack,
            // Base URI 用于相对请求
            'base_uri' => $this->buildUrl($config->get('nacos')),
            // 您可以设置任意数量的默认请求选项。
            'timeout' => $config->get('nacos.timeout'),
        ]);
    }

    public function get(string $url, array $params = [], array $header = [], array $options = []): string
    {
        if (!empty($params)) {
            $options['query'] = $this->filter($params);
        }
        if (!empty($header)) {
            $options['headers'] = $header;
        }
        $response = $this->sendRequest($url, 'GET', $options);

        return $response->getBody()->getContents();
    }

    protected function filter(array $input): array
    {
        $result = [];
        foreach ($input as $key => $value) {
            if ($value !== null) {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    private function sendRequest($url, string $method, array $options = []): ResponseInterface
    {
        $options = $this->getHttpRequestOptions($options);
        $response = $this->client->request($method, $url, $options);
        if ($response->getStatusCode() !== 200) {
            $errorMsg = sprintf("response error(%d):%s url:%s method:%s options:%s", $response->getStatusCode(), $response->getReasonPhrase(), $url, $method, json_encode($options));
            throw new HttpClientException($errorMsg);
        }
        return $response;
    }

    private function getHttpRequestOptions($params): array
    {
        return array_merge($this->httpRequestBaseOptions, $params);
    }

    protected function buildUrl(array $config, string $path = ''): string
    {
        return ($config['ssl'] ? 'https' : 'http') . '://' . $config['host'] . ':' . $config['port'] . $config['prefix'] . $path;
    }

    public function post(string $url, array $params = [], array $header = [], array $options = [], string $type = 'form_params'): string
    {
        if (!empty($params)) {
            $options[$type] = $this->filter($params);
        }
        if (!empty($header)) {
            $options['headers'] = $header;
        }
        $response = $this->sendRequest($url, 'POST', $options);

        return $response->getBody()->getContents();
    }

    public function put(string $url, array $params = [], array $header = [], array $options = [], string $type = 'form_params'): string
    {
        if (!empty($params)) {
            $options[$type] = $this->filter($params);
        }
        if (!empty($header)) {
            $options['headers'] = $header;
        }
        $response = $this->sendRequest($url, 'PUT', $options);

        return $response->getBody()->getContents();
    }


}