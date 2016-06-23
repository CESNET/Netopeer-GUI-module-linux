<?php

namespace FIT\Bundle\ModuleLinuxBundle\Models\Forms;

use Doctrine\Common\Collections\ArrayCollection;

class Ntp extends ConfigForm
{
    protected $enabled;

    protected $server;

    public function getEnabled()
    {
        return $this->enabled;
    }

    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    public function getServer()
    {
        return $this->server;
    }

    public function setServer($server)
    {
        $this->server = $server;
    }

    public function __construct()
    {
        $this->server = new ArrayCollection();
    }

    public static function createFromXml($ntpXml)
    {
        $ntp = new static();

        $ntp->setEnabled(Ntp::getNodeValue($ntpXml->enabled, false));
        $index = 1;
        foreach($ntpXml->server as $server) {
            $ntpServer = new NtpServer();

            $ntpServer->setName(Ntp::getNodeValue($server->name));
            $ntpServer->getUdp()->setAddress(Ntp::getNodeValue($server->udp->address));
            $ntpServer->getUdp()->setPort(Ntp::getNodeValue($server->udp->port));
            $ntpServer->setAssociationType(Ntp::getNodeValue($server->{'association-type'}));
            $ntpServer->setIburst(Ntp::getNodeValue($server->iburst));
            $ntpServer->setPrefer(Ntp::getNodeValue($server->prefer));

            $ntp->server->add($ntpServer);
            $index++;
        }

        return $ntp;
    }
}
