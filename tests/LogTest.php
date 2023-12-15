<?php
declare(strict_types=1);


use Exewen\Nacos\Facades\NacosLogger;

class LogTest extends \Exewen\Test\BaseTest
{

    /**
     * 生成配置
     * @return void
     */
    public function testSaveConfig()
    {
        NacosLogger::info("测试1", ['reqId' => 1111111, 'ss' => 2222222]);
        NacosLogger::info("测试2", ['reqId' => 1111111, 'ss' => 2222222]);
        NacosLogger::error("错误1", ['reqId' => 1111111, 'ss' => 2222222]);
        NacosLogger::error("错误2", ['reqId' => 1111111, 'ss' => 2222222]);
        var_dump('down');
        die();
    }


}