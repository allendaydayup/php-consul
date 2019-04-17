<?php
/**
 * Created by 20.
 * User: 20
 */
namespace Consul;

use Consul\Service;

include BASE_PATH . '/Consul/ConsulClient.php';//TODO 引入框架内此列去除

/**
 * Class Discovery
 * @package Reepu\Consul\Models
 */
class Discovery
{
    protected $apiClient;
    protected $cacheClient;
    protected $options;
    protected $keyProfix = 'php_consul_';

    /**
     * Discover constructor.
     * @param mixed $options
     */
    public function __construct($options) {
        $this->options = [
            "host" => "http://127.0.0.1:8500"
        ];
        if (isset($options["host"])) {
            $this->options["host"] = $options["host"];
        }
        $sf =  new ConsulClient(['host' => '47.92.48.222:18500']);
        $this->apiClient = $sf->health;

        //TODO 线上的话，修改为框架内调用方式
//        $this->cacheClient = new \Redis();
//        $this->cacheClient->connect("127.0.0.1","6379");
    }

    /**
     * 服务发现
     * @param string $serviceName //服务名，必填
     * @param boolean $cache //是否使用缓存
     * @param array $tags 选填 注册服务的时候传的tags，可以根据这个tags来区分同一个服务名的服务
     * @return array array('code' => 为零时正常返回, 'msg' => 提示, 'data' => 对象类型);
     */
    public function getService($serviceName, $cache = true, $tags = array()) {
        if (!$serviceName) {
            return array('code' => 101, 'msg' => '服务名不能为空', 'data' => (object)array());
        }

//        if ($cache) {
//            //获取当前服务名的缓存
//            $value = $this->cacheClient->get($keyProfix.$serviceName);
//            if ($value) {
//                $return = @unserialize($value);
//                shuffle($return);//随机取出一个
//                $return = new Service($return[0]["Service"]);
//                return array('code' => 0, 'msg' => 'ok', 'data' => $return);
//            }
//        }

        //发现服务
        $query = array(
            'service' => $serviceName,
            'near' => '_agent',
            'passing' => true,
        );
        if ($tags) {
            $query['tags'] = $tags;
        }
        $discoveryResponse = $this->apiClient->service($serviceName, $query);//return $discoveryResponse;
        if ($discoveryResponse === null) {
            array('code' => 102, 'msg' => '与Consul通信时出错', 'data' => (object)array());
        }
        if (empty($discoveryResponse)) {
            return array('code' => 104, 'msg' => sprintf("未找到服务： %s ", $serviceName), 'data' => (object)array());
        }

//        if ($cache) {
//            //缓存
//            $service = serialize($discoveryResponse);
//            $this->cacheClient->setex($keyProfix.$serviceName, 600, $service);//10min缓存失效时间
//        }

        shuffle($discoveryResponse);//随机取出一个
        $return = new Service($discoveryResponse[0]["Service"]);

        return array('code' => 0, 'msg' => 'ok', 'data' => $return);
    }

    /**
     * 删除一个或多个服务缓存
     * @param $serviceNames array(key1, key2, ...) 缓存服务的名字组成的数组
     * @return int 返回清除了几个键的缓存
     */
    public function clearCache($serviceNames, $all = false)
    {
        if (!$serviceNames) {
            return 0;
        }
        //redis
        if ($all) {
            $res = $this->cacheClient->del($keyProfix.'*');
        } else {
            $res = $this->cacheClient->del($serviceNames);
        }
        return $res;
    }
}