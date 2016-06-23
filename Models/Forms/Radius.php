<?php

namespace FIT\Bundle\ModuleLinuxBundle\Models\Forms;

use Doctrine\Common\Collections\ArrayCollection;

class Radius extends ConfigForm
{
    protected $server;

    protected $options;

    public function getServer()
    {
        return $this->server;
    }

    public function setServer($server)
    {
        $this->server = $server;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions($options)
    {
        $this->options = $options;
    }

    public function __construct()
    {
        $this->server = new ArrayCollection();
        $this->options = new Options();
    }

    public static function createFromXml($radiusXml)
    {
        $radius = new static();

        $index = 1;
        foreach($radiusXml->server as $server) {
            $radiusServer = new RadiusServer();

            $radiusServer->setName(Radius::getNodeValue($server->name));
            $radiusServer->getUdp()->setAddress(Radius::getNodeValue($server->udp->address));
            $radiusServer->getUdp()->setAuthenticationPort(Radius::getNodeValue($server->udp->{'authentication-port'}));
            $radiusServer->getUdp()->setSharedSecret(Radius::getNodeValue($server->udp->{'shared-secret'}));
            $radiusServer->setAuthenticationType(Radius::getNodeValue($server->{'authentication-type'}));

            $radius->server->add($radiusServer);
            $index++;
        }

        $radius->getOptions()->setTimeout(Radius::getNodeValue($radiusXml->options->timeout));
        $radius->getOptions()->setAttempts(Radius::getNodeValue($radiusXml->options->attempts));

        return $radius;
    }
}