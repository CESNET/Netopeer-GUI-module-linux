<?php

namespace FIT\Bundle\ModuleLinuxBundle\Models\Forms;

class InterfaceIpv4
{
    protected $enabled;

    protected $forwarding;

    protected $mtu;

    protected $address;

    protected $neighbor;

    public function getEnabled()
    {
        return $this->enabled;
    }

    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    public function getForwarding()
    {
        return $this->forwarding;
    }

    public function setForwarding($forwarding)
    {
        $this->forwarding = $forwarding;
    }

    public function getMtu()
    {
        return $this->mtu;
    }

    public function setMtu($mtu)
    {
        $this->mtu = $mtu;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function getNeighbor()
    {
        return $this->neighbor;
    }

    public function setNeighbor($neighbor)
    {
        $this->neighbor = $neighbor;
    }

    public function __construct()
    {
        // TODO: Address and neighbor should be arrays
        $this->address = new InterfaceAddress();
        $this->neighbor = new InterfaceNeighbor();
    }
}