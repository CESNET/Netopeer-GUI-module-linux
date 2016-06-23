<?php

namespace FIT\Bundle\ModuleLinuxBundle\Models\Forms;

use Doctrine\Common\Collections\ArrayCollection;

class Interfaces extends ConfigForm
{
    protected $interface;

    public function getInterface()
    {
        return $this->interface;
    }

    public function setInterface($interface)
    {
        $this->interface = $interface;
    }

    public function __construct()
    {
        $this->interface = new ArrayCollection();
    }

    public static function createFromXml($interfacesXml)
    {
        $interfaces = new static();
        $index = 1;

        foreach ($interfacesXml->interface as $interface) {
            $interfaceInformation = new InterfaceInformation();
            $interfaceInformation->setName(Interfaces::getNodeValue($interface->name));
            $interfaceInformation->setDescription(Interfaces::getNodeValue($interface->description));
            $interfaceInformation->setType(Interfaces::getNodeValue($interface->type));
            $interfaceInformation->setEnabled(Interfaces::getNodeValue($interface->enabled));
            $interfaceInformation->setLinkUpDownTrapEnable(Interfaces::getNodeValue($interface->{'link-up-down-trap-enable'}));

            if (isset($interface->ipv4)) {
                $interfaceInformation->getIpv4()->setEnabled(Interfaces::getNodeValue($interface->ipv4->enabled));
                $interfaceInformation->getIpv4()->setForwarding(Interfaces::getNodeValue($interface->ipv4->forwarding));
                $interfaceInformation->getIpv4()->setMtu(Interfaces::getNodeValue($interface->ipv4->mtu));

                if (isset($interface->ipv4->address)) {
                    $interfaceInformation->getIpv4()->getAddress()->setIp(Interfaces::getNodeValue($interface->ipv4->address->ip));
                    $interfaceInformation->getIpv4()->getAddress()->setPrefixLength(Interfaces::getNodeValue($interface->ipv4->address->{'prefix-length'}));
                    $interfaceInformation->getIpv4()->getAddress()->setNetmask(Interfaces::getNodeValue($interface->ipv4->address->netmask));
                }

                if (isset($interface->ipv4->neighbor)) {
                    $interfaceInformation->getIpv4()->getNeighbor()->setIp(Interfaces::getNodeValue($interface->ipv4->neighbor->ip));
                    $interfaceInformation->getIpv4()->getNeighbor()->setLinkLayerAddress(Interfaces::getNodeValue($interface->ipv4->neighbor->{'link-layer-address'}));
                }
            }

            $interfaces->interface->add($interfaceInformation);
            $index++;
        }

        return $interfaces;
    }
}