<?php

namespace FIT\Bundle\ModuleLinuxBundle\Models\Forms;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

class RadiusServer
{
    /**
     * @Assert\NotBlank(message="Server name value must be filled in")
     */
    protected $name;

    protected $udp;

    protected $authenticationType;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getUdp()
    {
        return $this->udp;
    }

    public function setUdp($udp)
    {
        $this->udp = $udp;
    }

    public function getAuthenticationType()
    {
        return $this->authenticationType;
    }

    public function setAuthenticationType($authenticationType)
    {
        $this->authenticationType = $authenticationType;
    }

    public function __construct()
    {
        $this->udp = new RadiusServerUdp();
    }

    public static function createFromServerNumber($serverNumber)
    {
        $radiusServer = new static();
        $radiusServer->name = "server-".$serverNumber;
        return $radiusServer;
    }
}