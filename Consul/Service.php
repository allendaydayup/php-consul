<?php
/**
 * Created by 20.
 * User: 20
 */
namespace Consul;

class Service
{
    protected $serviceID;
    protected $name;
    protected $tags;
    protected $address;
    protected $port;

    /**
     * Service constructor.
     * @param mixed $serviceData
     */
    public function __construct($serviceData = null) {
        if ($serviceData !== null) {
            $this->serviceID = $serviceData["ID"];
            $this->name = $serviceData["Service"];
            $this->address = $serviceData["Address"];
            $this->port = $serviceData["Port"];
            $this->tags = $serviceData["Tags"];
        }
    }

    /**
     * @return mixed
     */
    public function getID() {
        return $this->serviceID;
    }

    /**
     * @param mixed $serviceID
     */
    public function setID($serviceID) {
        $this->serviceID = $serviceID;
    }

    /**
     * @return mixed
     */
    public function getPort() {
        return $this->port;
    }

    /**
     * @param mixed $port
     */
    public function setPort($port) {
        $this->port = $port;
    }

    /**
     * @return mixed
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address) {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getTags() {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags) {
        $this->tags = $tags;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name) {
        $this->name = $name;
    }
}