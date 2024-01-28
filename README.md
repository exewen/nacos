## 安装组件
```sh
composer require exewen/nacos
```
## 复制配置
```sh
cp -rf ./publish/exewen /your_project/config
``` 
## 初始化
```php
!defined('BASE_PATH_PKG') && define('BASE_PATH_PKG', dirname(__DIR__, 1));
# 设置配置保存路径
\Exewen\Utils\FileUtil::setSnapshotPath(dirname(__DIR__) . "/config/nacos/env");

$app = new Container();
// 服务注册
$app->setProviders([LoggerProvider::class,HttpProvider::class,NacosProvider::class]);
$this->app = $app;
``` 
## nacos
```php
# 获取配置文件
/** @var NacosInterface $nacos */
$nacos = $this->app->get(NacosInterface::class);
$config = $nacos->getConfig($this->namespaceId, $this->dataId, $this->group);

# 获取并保存配置文件
/** @var NacosInterface $nacos */
$nacos = $this->app->get(NacosInterface::class);
$config = $nacos->saveConfig($this->namespaceId, $this->dataId, $this->group);

# 读取本地配置文件
$configPath = \Exewen\Utils\FileUtil::getSnapshotFile($this->namespaceId, $this->dataId, $this->group);

# 注册实例
/** @var NacosInterface $nacos */
$nacos = $this->app->get(NacosInterface::class);
$ip = '10.0.2.143';
$port = 8081;
$result = $nacos->setInstance($this->namespaceId, $this->serviceName, $this->group, $ip, $port);

# 发送心跳
/** @var NacosInterface $nacos */
$nacos = $this->app->get(NacosInterface::class);
$ip = '10.0.2.143';
$port = 8081;
$result = $nacos->setInstanceBeat($this->namespaceId, $this->serviceName, $this->group, $ip, $port);

# 获取实例列表
/** @var NacosInterface $nacos */
$nacos = $this->app->get(NacosInterface::class);
$result = $nacos->getInstance($this->namespaceId, $this->serviceName, $this->group, true);
```
## 使用 facades
```sh
composer require exewen/facades
```
```php
!defined('BASE_PATH_PKG') && define('BASE_PATH_PKG', dirname(__DIR__, 1));
# 设置配置保存路径
\Exewen\Utils\FileUtil::setSnapshotPath(dirname(__DIR__) . "/config/nacos/env");

# 获取配置文件
NacosFacade::getConfig($this->namespaceId, $this->dataId, $this->group);

# 获取并保存配置文件
NacosFacade::saveConfig($this->namespaceId, $this->dataId, $this->group);

# 读取本地配置文件
$configPath = \Exewen\Utils\FileUtil::getSnapshotFile($this->namespaceId, $this->dataId, $this->group);

# 注册实例
NacosFacade::setInstance($this->namespaceId, $this->serviceName, $this->group, $ip, $port);

# 发送心跳
NacosFacade::setInstanceBeat($this->namespaceId, $this->serviceName, $this->group, $ip, $port);

# 获取实例列表
NacosFacade::getInstance($this->namespaceId, $this->serviceName, $this->group, true);
```