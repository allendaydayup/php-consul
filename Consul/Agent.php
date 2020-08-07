<?php
/**
 * Created by 20.
 * User: 20
 */
namespace Consul;

use Consul\Service\Service;

include BASE_PATH . '/Consul/ConsulClient.php';//TODO 引入框架内此列去除

/**
 * Class Agent
 * @package Reepu\Consul\Models
 */
class Agent
{
    protected $apiClient;
    protected $options;

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
        $sf =  new ConsulClient(['host' => $this->options["host"]]);
        $this->apiClient = $sf->agent;
    }

    /**
     * 服务注册，使用前请看参数类型，按类型传参
     * @param string $id   //服务id
     * @param string $name //服务名称
     * @param string $ip   //服务注册到consul的IP，服务发现，发现的就是这个IP
     * @param array $tags //服务tag，数组，自定义，可以根据这个tag来区分同一个服务名的服务，array('secure=false')代表是当前http协议，array('secure=true')代表当前https，数组内容自行增减
     * @param integer $port //服务IP对应端口号,
     * @param string $healthCheckIp  //健康检查ip，一般与注册ip相同，需拼接协议
     * @param integer $healthCheckPort  //健康检查ip对应端口
     * @param string $healthCheckPath  //与IP和port拼接作为健康检查接口，对应的path，如:consul/health
     * @param string $interval  //健康检查间隔时间，默认每隔10s，调用一次拼接好的健康检查地址URL
     * @return array array('code' => 为零时正常返回, 'msg' => 提示, 'data' => 对象类型)
     */
    public function registerService($id, $name, $ip, $tags = ['secure=false'], $port = 80, $healthCheckIp = '', $healthCheckPort = 80, $healthCheckPath = '', $interval = '10s')
    {
        if (!$name) {
            return array('code' => 101, 'msg' => '服务名称不能为空', 'data' => (object)array());
        }

        $healthCheckIp = $healthCheckIp ? $healthCheckIp : $ip;

        $data = array(
            'id' => $id,
            'name' => $name,
            'tags' => $tags,
            'address' => $ip,
            'port' => (int)$port,
            'enabletagoverride' => false,
            'check' => array(
                'deregistercriticalserviceafter' => '90m',
                'http' => $healthCheckIp.':'.$healthCheckPort.'/'.$healthCheckPath, //指定健康检查的URL，调用后只要返回20X，consul都认为是健康的(我们是返回SUACCESS)
                'interval' => $interval,
            ), //健康检查部分
        );

        $res = $this->apiClient->put('service','register', $data);
        if ($res === NULL) {
            return array('code' => 103, 'msg' => '与Consul通信时出错', 'data' => (object)array());
        }
        return array('code' => 0, 'msg' => 'ok', 'data' => $res);
    }

    /**
     * 服务维护状态更改
     * @param string $service_id    //服务id，必填
     * @param boolean $enable       //true启用维护模式，false禁用维护模式，必填
     * @param string $reason        //更改原因，选填
     * @return array array('code' => 为零时正常返回, 'msg' => 提示, 'data' => 对象类型)
     */
    public function maintenance($service_id, $enable, $reason = '')
    {
        if (!$service_id) {
            return array('code' => 101, 'msg' => '服务id不能为空', 'data' => (object)array());
        }
        if (!is_bool($enable)) {
            return array('code' => 102, 'msg' => '维护状态必须是bool类型', 'data' => (object)array());
        }
        $data = array(
            'service_id' => $service_id,//required
            'enable' => $enable,//required
            'reason' => $reason,
        );
        $response = $this->apiClient->put('service', 'maintenance', $service_id, $data, 'build_url');
        if ($response === NULL) {
            return array('code' => 103, 'msg' => '服务不存在或通信出错', 'data' => (object)array());
        }

        return array('code' => 0, 'msg' => 'ok', 'data' => $response);
    }

    /**
     * 注销服务
     * @param string $service_id   //服务id，必填
     * @return array array('code' => 为零时正常返回, 'msg' => 提示, 'data' => 对象类型)
     */
    public function deregisterService($service_id)
    {
        if (!$service_id) {
            return array('code' => 101, 'msg' => '服务id不能为空', 'data' => (object)array());
        }
        $response = $this->apiClient->put('service', 'deregister', $service_id);

        return array('code' => 0, 'msg' => 'ok', 'data' => $response);
    }
}
