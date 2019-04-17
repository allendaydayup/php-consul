<?php

namespace Consul;

final class ConsulResponse
{
    private $headers;
    private $body;
    private $status;

    public function __construct($headers, $body, $status = 200)
    {
        $this->headers = $headers;
        $this->body = $body;
        $this->status = $status;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getBody()
    {
        return $this->getArray();
    }

    public function getStatusCode()
    {
        return $this->status;
    }

    public function getJson()
    {
        return $this->body;
    }

    public function getArray()
    {
        return json_decode($this->body, true);
    }
}
