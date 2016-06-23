<?php

namespace FIT\Bundle\ModuleLinuxBundle\Models\Forms;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

class NtpServer
{
    /**
     * @Assert\NotBlank(message="Server name value must be filled in")
     */
    protected $name;

    protected $udp;

    protected $associationType;

    protected $iburst;

    protected $prefer;

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

    public function getAssociationType()
    {
        return $this->associationType;
    }

    public function setAssociationType($associationType)
    {
        $this->associationType = $associationType;
    }

    public function getIburst()
    {
        return $this->iburst;
    }

    public function setIburst($iburst)
    {
        $this->iburst = $iburst;
    }

    public function getPrefer()
    {
        return $this->prefer;
    }

    public function setPrefer($prefer)
    {
        $this->prefer = $prefer;
    }

    public function __construct()
    {
        $this->udp = new Udp();
    }

    public static function createFromServerNumber($serverNumber)
    {
        $ntpServer = new static();

        $ntpServer->name = "server-".$serverNumber;
        $ntpServer->associationType = "server";

        return $ntpServer;
    }
}

