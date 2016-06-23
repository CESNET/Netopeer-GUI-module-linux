<?php

namespace FIT\Bundle\ModuleLinuxBundle\Models\Forms;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

class DnsResolverServer
{
    /**
     * @Assert\NotBlank(message="Server name value must be filled in")
     */
    protected $name;

    protected $udpAndTcp;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getUdpAndTcp()
    {
        return $this->udpAndTcp;
    }

    public function setUdpAndTcp($udpAndTcp)
    {
        $this->udpAndTcp = $udpAndTcp;
    }

    public function __construct()
    {
        $this->udpAndTcp = new Udp();
    }

    public static function createFromServerNumber($serverNumber)
    {
        $dnsServer = new static();
        $dnsServer->name = "nameserver-".$serverNumber;
        return $dnsServer;
    }
}