<?php
/**
 * Created by 20.
 * User: 20
 * 服务维护，维护之后，服务将不会被发现
 */
define('BASE_PATH', __DIR__);
include BASE_PATH . '/Consul/Agent.php';


//eg:

$enable     = false;//true启用维护模式，false禁用维护模式
$service_id = 'win1-80';
$reason     = 'maintenance 1h';//原因，自定义，可空

$agent = new Consul\Agent(array(
    'host' => 'http://127.0.0.1:8500'
));

$res = $agent->maintenance($service_id, $enable, $reason);

echo "<pre>";
var_dump($res);