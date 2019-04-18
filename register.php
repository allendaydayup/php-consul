<?php
/**
 * Created by 20.
 * User: 20
 * 服务注册
 */
define('BASE_PATH', __DIR__);
include BASE_PATH . '/Consul/Agent.php';


//eg:

//判断当前协议
$http_type = 'http://';//当前协议是http或https
$secure = 'secure=false';//是否是https协议，https:secure=true
if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) {
    $http_type = 'https://';
    $secure = 'secure=true';
}

//ip,port默认是本机
$ip                 = '127.0.0.1';//$_SERVER['SERVER_ADDR']
$port               = 80;////$_SERVER['SERVER_PORT']
$name               = 'win1';//自定义
$id                 = 'win1-7'.$port;//自定义
$tags               = array( $secure );//服务的tag，自定义增加值，可以根据这个tag来区分同一个服务名的服务
$healthCheckIp      = $http_type.$ip;//健康检查ip默认与注册一样,但需拼接协议，如不同可修改
$healthCheckPort    = 80;
$healthCheckPath    = 'health.php';//健康检查path,如consul/health
$interval           = '10s';//健康检查间隔

$agent = new Consul\Agent(array(
    'host' => 'http://127.0.0.1:8500'
));

$res = $agent->registerService($id, $name, $ip, $tags, $port, $healthCheckIp, $healthCheckPort, $healthCheckPath, $interval);

echo "<pre>";
var_dump($res);

