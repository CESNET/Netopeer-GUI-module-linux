<?php
/**
 * Created by PhpStorm.
 * User: Honza
 * Date: 3.5.2016
 * Time: 23:33
 */

namespace FIT\Bundle\ModuleLinuxBundle\Models\Forms;


class SystemIdentification extends ConfigForm
{
    public $contact;

    public $hostname;

    public $location;

    /**
     * @return mixed
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @param mixed $contact
     */
    public function setContact($contact)
    {
        $this->contact = $contact;
    }

    /**
     * @return mixed
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * @param mixed $hostname
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }



 /*   public function __construct()
    {
       // parent::__construct();
    }*/

    public static function createFormFromXml($systemXml)
    {
        $identification = new static();

        $identification->contact = SystemIdentification::getNodeValue($systemXml->contact);
      //  $identification->contactXpath = "contact_--system--xmlns:contact";
        $identification->hostname = SystemIdentification::getNodeValue($systemXml->hostname);
      //  $identification->hostnameXpath = "hostname_--system--xmlns:hostname";
        $identification->location = SystemIdentification::getNodeValue($systemXml->location);
      //  $identification->locationXpath = "location_--system--xmlns:location";

        return $identification;
    }
}