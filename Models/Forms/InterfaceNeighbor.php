<?php

namespace FIT\Bundle\ModuleLinuxBundle\Models\Forms;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

class InterfaceNeighbor
{
    /**
     * @Assert\NotBlank(message="Interface neighbor ip value must be filled in")
     */
    protected $ip;

    /**
     * @Assert\NotBlank(message="Interface neighbor link-layer-address value must be filled in")
     */
    protected $linkLayerAddress;

    public function getIp()
    {
        return $this->ip;
    }

    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    public function getLinkLayerAddress()
    {
        return $this->linkLayerAddress;
    }

    public function setLinkLayerAddress($linkLayerAddress)
    {
        $this->linkLayerAddress = $linkLayerAddress;
    }
}