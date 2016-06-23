<?php

namespace FIT\Bundle\ModuleLinuxBundle\Models\Forms;

use Doctrine\Common\Collections\ArrayCollection;

class DnsResolver extends ConfigForm
{
    protected $search;

    protected $server;

    protected $options;

    public function getSearch()
    {
        return $this->search;
    }

    public function setSearch($search)
    {
        $this->search = $search;
    }

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
        $this->search = new ArrayCollection();
        $this->server = new ArrayCollection();
        $this->options = new Options();
    }

    public static function createFromXml($dnsXml)
    {
        $dns = new static ();

        $index = 1;
        if (empty($dnsXml->search)) {
            $dnsSearch = new DnsResolverSearch();
            $dns->search->add($dnsSearch);
        }
        else {
            foreach ($dnsXml->search as $search) {
                $dnsSearch = new DnsResolverSearch();
                $dnsSearch->setSearch(DnsResolver::getNodeValue($search));
                $dns->search->add($dnsSearch);
                $index++;
            }
        }

        $index = 1;
        foreach($dnsXml->server as $server) {
            $dnsServer = new DnsResolverServer();
            $dnsServer->setName(DnsResolver::getNodeValue($server->name));
            $dnsServer->getUdpAndTcp()->setAddress(DnsResolver::getNodeValue($server->{'udp-and-tcp'}->address));
            $dnsServer->getUdpAndTcp()->setPort(DnsResolver::getNodeValue($server->{'udp-and-tcp'}->port));
            $dns->server->add($dnsServer);
            $index++;
        }
        $dns->getOptions()->setTimeout(DnsResolver::getNodeValue($dnsXml->options->timeout));
        $dns->getOptions()->setAttempts(DnsResolver::getNodeValue($dnsXml->options->attempts));

        return $dns;
    }
}