<?php

namespace FIT\Bundle\ModuleLinuxBundle\Models\Forms;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

class Udp
{
    /**
     * @Assert\NotBlank(message="Server address value must be filled in")
     */
    protected $address;

    protected $port;

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function getPort()
    {
        return $this->port;
    }
    
    public function setPort($port)
    {
        $this->port = $port;
    }
}