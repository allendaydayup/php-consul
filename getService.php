<?php
/**
 * Created by 20.
 * User: 20
 * 发现服务,获取服务内所有节点，随机返回其中一个
 */
define('BASE_PATH', __DIR__);
include BASE_PATH . '/Consul/Discovery.php';
//eg:

$serviceName = 'win2';//注册时的name
$cache = false;//是否使用缓存，默认false

$discovery = new Consul\Discovery(array(
    'host' => 'http://127.0.0.1:8500'
));
$service = $discovery->getService($serviceName, $cache);

echo "<pre>";
var_dump($service);
//var_dump($service['data']->getID());
//var_dump($service['data']->getPort());
//var_dump($service['data']->getAddress());
//var_dump($service['data']->getName());
//var_dump($service['data']->getTags());