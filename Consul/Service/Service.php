<?php
namespace Consul\Service;

use Consul\Http;

class Service
{
    /**
     * @var Http
     */
    protected $http;
    protected $base_url = '';
    public $name = 'agent';

    public function response($str)
    {
        if ($str === false) {
            return false;
        }
        if (empty($str)) {
            return '';
        }
        return json_decode($str, true);
    }

    public function __construct($base_url, $http)
    {
        $this->base_url = $base_url;
        $this->http = $http;
    }

    public function header()
    {
        $func_args = func_get_args();
        if (count($func_args) == 0) {
            return false;
        }
        $method_name = $func_args[0];
        $param = array();
        $url = $this->name . '/' . $method_name;
        for ($i = 1; $i < count($func_args); $i++) {
            if (is_array($func_args[$i])) {
                $param = array_merge($param, $func_args[$i]);
            } else {
                $url .= '/' . $func_args[$i];
            }
        }
        $resp = $this->http->header($this->base_url . $url, $param);
        return $resp;
    }

    public function __call($method_name, $args)
    {
        $url = $this->name . '/' . $method_name;
        if (empty($args)) {
            $resp = $this->http->request($this->base_url . $url);
        } else {
            $param = array();
            foreach ($args as $arg) {
                if (is_array($arg)) {
                    $param = array_merge($param, $arg);
                } else {
                    $url .= '/' . $arg;
                }
            }
            $resp = $this->http->request($this->base_url . $url, $param);
        }
        return $this->response($resp);
    }

    public function put()
    {
        $args=func_get_args();
        $url = $this->name ;
        if (empty($args)) {
            $resp = $this->http->request($this->base_url . $url);
        } else {
            $param = array();
            $build_url = false;
            foreach ($args as $arg) {
                if (is_array($arg)) {
                    $param = array_merge($param, $arg);
                } else {
                    if ($arg == 'build_url') {
                        $build_url = $arg;
                        continue;
                    }
                    $url .= '/' . $arg;
                }
            }
            if (empty($param)) {
                $resp = $this->http->request($this->base_url . $url, array(), 'PUT');
            } else {
                if ($build_url) {
                    $resp = $this->http->request($this->base_url . $url, array('__body' => json_encode($param), 'build_url' => true), 'PUT');
                } else {
                    $resp = $this->http->request($this->base_url . $url, array('__body' => json_encode($param)), 'PUT');
                }
            }
        }
        return $this->response($resp);
    }
}
