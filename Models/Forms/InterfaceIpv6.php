<?php
/**
 * Created by PhpStorm.
 * User: Honza
 * Date: 7.6.2016
 * Time: 22:42
 */

namespace FIT\Bundle\ModuleLinuxBundle\Models\Forms;


class InterfaceIpv6
{
    public $ipv4EnabledValue;

    public $ipv4EnabledXpath;

    public $forwardingValue;

    public $forwardingXpath;

    public $mtuValue;

    public $mtuXpath;

    public $address;

    public $neighbor;

    // dup-addr-detect-transmits
    // autoconf

    public function __construct()
    {
        $this->address = array();
        $this->neighbor = array();
    }
}