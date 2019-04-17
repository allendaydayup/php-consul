<?php
namespace Consul;

use Consul\Service\Service;
use Consul\ConsulResponse;

class ConsulClient
{
    private $http;
    private $base_url = '';
    private $service = array();

    public function __construct($option = array())
    {
        $default = array('host' => '127.0.0.1:8500', 'url' => '/v1/');
        $default = array_replace($default, $option);
        $this->base_url = $default['host'] . $default['url'];
        $this->http = new Http();
        if (!empty($default['token'])) {
            $this->http->query['token'] = $default['token'];
        }
    }

    /**
     * 用魔术属性获取服务
     *
     * @param string $name 服务名
     *
     * @return Service 服务
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * 获取服务
     *
     * @param string $name 服务名
     *
     * @return Service 服务
     */
    public function get($name)
    {
        $name = ucfirst($name);
        if (empty($this->service[$name])) {
            $class_name = "Consul\\Service\\$name";
            $this->service[$name] = new $class_name($this->base_url, $this->http);
        }
        return $this->service[$name];
    }

}

/**
 * 自动注册服务类
 */
spl_autoload_register(function ($class) {
    $file = dirname(__DIR__) . DIRECTORY_SEPARATOR.str_replace("\\", DIRECTORY_SEPARATOR, $class) . '.php';
    if (is_file($file)) {
        require($file);
    }
}
);