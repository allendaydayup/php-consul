<?php
namespace Consul\Service;
class KV extends Service
{
    public function __construct($base_url, $http)
    {
        parent::__construct($base_url, $http);
        $this->name = 'kv';
    }

    public function get($key, array $param = array())
    {
        $url = $this->name . '/' . $key;
        $resp = $this->http->request($this->base_url . $url, $param);
        return $this->response($resp, empty($param['recurse'])?false:$param['recurse']);
    }

    public function delete($key, array $param = array())
    {
        $url = $this->name . '/' . $key;
        $resp = $this->http->request($this->base_url . $url, $param, 'DELETE');
        return $resp == 'true';
    }

    public function set($key, $value, array $param = array())
    {
        $url = $this->name . '/' . $key;
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value);
        }
        $param['__body'] = $value;
        $resp = $this->http->request($this->base_url . $url, $param, 'PUT');
        return $resp == 'true';
    }

    public function response($str, $isArray = false)
    {
        $json = parent::response($str);
        if (is_array($json)) {
            if (count($json) == 1 && $isArray == false) {
                return base64_decode($json[0]['Value']);
            } else {
                $re = array();
                foreach ($json as $v) {
                    $re[$v['Key']] = base64_decode($v['Value']);
                }
                return $re;
            }
        } else {
            return $json;
        }
    }
}