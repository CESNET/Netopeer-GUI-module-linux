<?php

namespace FIT\Bundle\ModuleLinuxBundle\Models\Forms;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

class InterfaceAddress
{
    /**
     * @Assert\NotBlank(message="Interface address ip value must be filled in")
     */
    protected $ip;

    protected $prefixLength;

    protected $netmask;

    public function getIp()
    {
        return $this->ip;
    }

    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    public function getPrefixLength()
    {
        return $this->prefixLength;
    }

    public function setPrefixLength($prefixLength)
    {
        $this->prefixLength = $prefixLength;
    }

    public function getNetmask()
    {
        return $this->netmask;
    }

    public function setNetmask($netmask)
    {
        $this->netmask = $netmask;
    }
}