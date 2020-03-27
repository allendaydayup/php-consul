# php-consul

## 使用方法

#### 仔细的看consul官方接口文档
使用sdk需要你仔细的了解consul的http的API。

官方HTTP API接口文档：https://www.consul.io/api/index.html

#### 内代码有实现怎么调用的~
代码例子中已实现：服务注册，服务发现，服务注销，服务维护

例如：getService.php，中示例了服务发现的使用方法
```
$serviceName = 'win2';//注册时的name
$cache = false;//是否使用缓存，默认false

$discovery = new Consul\Discovery(array(
    'host' => 'http://127.0.0.1:8500'
));
$service = $discovery->getService($serviceName, $cache);
```
