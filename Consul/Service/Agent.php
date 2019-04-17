<?php
namespace Consul\Service;
class Agent extends Service
{
    public function __construct($base_url, $http)
    {
        parent::__construct($base_url, $http);
        $this->name = 'agent';
    }
}