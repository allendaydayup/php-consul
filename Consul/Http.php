<?php
namespace Consul;
class Http
{
    /**
     * @var array 公用的header
     */
    private $headers = array(
        'User-Agent' => 'php-consul-sdk',
        'Content-Type' => 'application/x-www-form-urlencoded',
        'Cache-Control' => 'max-age=0',
        'Accept' => '*/*',
        'Accept-Language' => 'zh-CN,zh;q=0.8',
    );

    /**
     * @var array 公用的URL参数
     */
    public $query = array();
    public $http_prefix = 'http://';

    /**
     * http的语法
     *
     * @param    string $url 地址
     * @param array    $param 参数
     * @param string    $method 请求类型
     * @param array     $header 自定义头
     *
     * @return string
     */
    public function request($url, array $param = array(), $method = 'GET', $header = array())
    {
        $query = array();
        $header = array_merge($header, $this->headers);
        $body = '';
        $to_query = array();
        if (!empty($param['__body'])) {
            $body = $param['__body'];
            unset($param['__body']);
            if (isset($param['build_url']) && $param['build_url']) {
                $to_query = json_decode($body, true);
                unset($param['build_url']);
            }
        }
        if (strcasecmp($method, 'get') === 0) {
            $query = array_merge($this->query, $param);
        } else {
            if (empty($body)) {
                $body = http_build_query($param);
                $query = array_merge($this->query);
            } else {
                $query = array_merge($this->query, $param);
                if ($to_query) {
                    $query = array_merge($this->query, $to_query);
                }
            }
        }
        if (strpos($url, '?') === false) {
            $url .= '?' . http_build_query($query);
        } else {
            $url .= '&' . http_build_query($query);
        }
        $ch = curl_init();
        if ($this->http_prefix == 'https://') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($ch, CURLOPT_URL, $this->http_prefix . $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method); //设置请求方式
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        $content = curl_exec($ch);
        $status = curl_errno($ch);
        if ($status === 0) {
            return $content;
        } else {
            return false;
        }
    }

    public function header($url, array $param = array(), $method = 'GET', $header = array())
    {
        $query = array();
        $header = array_merge($header, $this->headers);
        $body = '';
        if (!empty($param['__body'])) {
            $body = $param['__body'];
            unset($param['__body']);
        }
        if (strcasecmp($method, 'get') === 0) {
            $query = array_merge($this->query, $param);
        } else {
            if (empty($body)) {
                $body = http_build_query($param);
                $query = array_merge($this->query);
            } else {
                $query = array_merge($this->query, $param);
            }
        }
        if (strpos($url, '?') === false) {
            $url .= '?' . http_build_query($query);
        } else {
            $url .= '&' . http_build_query($query);
        }

        $ch = curl_init();
        if ($this->http_prefix == 'https://') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($ch, CURLOPT_URL, $this->http_prefix . $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method); //设置请求方式
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        $content = curl_exec($ch);
        $status = curl_errno($ch);
        curl_close($ch);
        $header = array();
        if ($status === 0) {
            if (($index = strpos($content, "\r\n\r\n")) !== false) {
                $header_body = substr($content, 0, $index);
                $header_body = explode("\r\n", $header_body);
                foreach ($header_body as $r) {
                    $index = strpos($r, ':');
                    if ($index !== false) {
                        $header[trim(substr($r, 0, $index))] = trim(substr($r, $index + 1));
                    }
                }
            }
        }
        return $header;
    }
}