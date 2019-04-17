<?php
namespace Consul\Service;
class Health extends Service
{
    public function __construct($base_url, $http)
    {
        parent::__construct($base_url, $http);
        $this->name = 'health';
    }
}