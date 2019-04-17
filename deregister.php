<?php
/**
 * Created by 20.
 * User: 20
 * 服务注销
 */
define('BASE_PATH', __DIR__);
include BASE_PATH . '/Consul/Agent.php';

//eg:

$service_id = 'win1-780';//注册时的id
$agent = new Consul\Agent(array(
    'host' => 'http://127.0.0.1:8500'
));
$res = $agent->deregisterService($service_id);

echo "<pre>";
var_dump($res);