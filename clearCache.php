<?php
/**
 * Created by 20.
 * User: 20
 * 清除服务节点缓存
 */
define('BASE_PATH', __DIR__);
include BASE_PATH . '/Consul/Discovery.php';

//eg:

$serviceNames = array('win1');//array(key1, key2, ...)，可一个，可多个
$all = false;//是否全部清除consul相关的缓存,默认：false 不，如果要清除全部 传true

$discovery = new Consul\Discovery(array(
    'host' => 'http://127.0.0.1:8500'
));
$res = $discovery->clearCache($serviceNames, $all);

var_dump($res);