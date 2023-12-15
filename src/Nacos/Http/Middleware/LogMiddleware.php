<?php

namespace Exewen\Nacos\Http\Middleware;

use Exewen\Nacos\Facades\NacosLogger;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class LogMiddleware
{
    public function __invoke(callable $handler): callable
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            // 打印请求原始参数
            $start = microtime(true);
            // 发送请求并获取响应
            return $handler($request, $options)->then(function (ResponseInterface $response) use ($request, $start) {
                $this->logSaveOk($request, $response, $start);
                return $response;
            })->otherwise(function (\Throwable $exception) use ($request, $start) {
                $this->logSaveError($request, $exception->getTraceAsString(), $start);
                throw $exception;
            });
        };
    }

    protected function logSaveOk(RequestInterface $request, ResponseInterface $response, $start)
    {
        $cost = round(microtime(true) - $start, 3); // 保留3位小数点
        $request->getBody()->rewind();
        $log = [
            'req_cost' => $cost,
            'method' => $request->getMethod(),
            'url' => $request->getUri(),
            'header' => $request->getHeaders(),
            'request_contents' => $request->getBody()->getContents(),
            'response_code' => $response->getStatusCode(),
            'response_cost' => $cost,
            'response_header' => $response->getHeaders(),
            'response_contents' => $response->getBody()->getContents(),
        ];
        $response->getBody()->rewind();
        NacosLogger::request(json_encode($log, JSON_UNESCAPED_UNICODE));
    }

    protected function logSaveError(RequestInterface $request, $errorMsg, $start)
    {
        $cost = round(microtime(true) - $start, 3); // 保留3位小数点
        $request->getBody()->rewind();
        $log = [
            'req_cost' => $cost,
            'method' => $request->getMethod(),
            'url' => $request->getUri(),
            'header' => $request->getHeaders(),
            'request_contents' => $request->getBody()->getContents(),
            'response_cost' => $cost,
            'error_message' => $errorMsg,
        ];
        NacosLogger::error(json_encode($log, JSON_UNESCAPED_UNICODE));
    }

}