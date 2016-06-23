<?php

namespace FIT\Bundle\ModuleLinuxBundle\Models\Forms;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

class InterfaceInformation
{
    /**
     * @Assert\NotBlank(message="Interface name value must be filled in")
     */
    protected $name;

    protected $description;

    /**
     * @Assert\NotBlank(message="Interface type value must be filled in")
     */
    protected $type;

    protected $enabled;

    protected $linkUpDownTrapEnable;

    protected $ipv4;

  //  protected $ipv6;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getEnabled()
    {
        return $this->enabled;
    }

    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    public function getLinkUpDownTrapEnable()
    {
        return $this->linkUpDownTrapEnable;
    }

    public function setLinkUpDownTrapEnable($linkUpDownTrapEnable)
    {
        $this->linkUpDownTrapEnable = $linkUpDownTrapEnable;
    }

    public function getIpv4()
    {
        return $this->ipv4;
    }

    public function setIpv4($ipv4)
    {
        $this->ipv4 = $ipv4;
    }

  /*  public function getIpv6()
    {
        return $this->ipv6;
    }

    public function setIpv6($ipv6)
    {
        $this->ipv6 = $ipv6;
    }*/

    public function __construct()
    {
        $this->ipv4 = new InterfaceIpv4();
     //   $this->ipv6 = new InterfaceIpv6();
    }

    public static function createFromInterfaceNumber($interfaceNumber)
    {
        $interfaceInformation = new static();
     /*   $interfaceInformation->nameValue = null;
        $interfaceInformation->nameXpath = "name_--interfaces--xmlns:interface?".$interfaceNumber."!--xmlns:name";
        $interfaceInformation->descriptionValue = null;
        $interfaceInformation->descriptionXpath = "description_--interfaces--xmlns:interface?".$interfaceNumber."!--xmlns:description";
        $interfaceInformation->typeValue = null;
        $interfaceInformation->typeXpath = "type_--interfaces--xmlns:interface?".$interfaceNumber."!--xmlns:type";
        $interfaceInformation->enabledValue = null;
        $interfaceInformation->enabledXpath = "enabled_--interfaces--xmlns:interface?".$interfaceNumber."!--xmlns:enabled";
        $interfaceInformation->linkUpDownTrapEnableValue = null;
        $interfaceInformation->linkUpDownTrapEnableXpath = "link-up-down-trap-enable_--interfaces--xmlns:interface?".$interfaceNumber."!--xmlns:link-up-down-trap-enable";

        $interfaceInformation->ipv4->ipv4EnabledXpath = "enabled_--interfaces--xmlns:interface?".$interfaceNumber."!--xmlns:ipv4--xmlns:enabled";
        $interfaceInformation->ipv4->ipv4EnabledValue = null;
        $interfaceInformation->ipv4->forwardingXpath = "forwarding_--interfaces--xmlns:interface?".$interfaceNumber."!--xmlns:ipv4--xmlns:forwarding";
        $interfaceInformation->ipv4->forwardingValue = null;
        $interfaceInformation->ipv4->mtuXpath = "mtu_--interfaces--xmlns:interface?".$interfaceNumber."!--xmlns:ipv4--xmlns:mtu";
        $interfaceInformation->ipv4->mtuValue = null;

        $interfaceInformation->formName = "configDataForm";
        $interfaceInformation->interfaceXpath = "--*--xmlns:interface?".$interfaceNumber."!";*/

        return $interfaceInformation;
    }
}