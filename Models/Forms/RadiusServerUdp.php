<?php

namespace FIT\Bundle\ModuleLinuxBundle\Models\Forms;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

class RadiusServerUdp
{
    /**
     * @Assert\NotBlank(message="Server address value must be filled in")
     */
    protected $address;

    protected $authenticationPort;

    /**
     * @Assert\NotBlank(message="Server shared secret value must be filled in")
     */
    protected $sharedSecret;

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function getAuthenticationPort()
    {
        return $this->authenticationPort;
    }

    public function setAuthenticationPort($authenticationPort)
    {
        $this->authenticationPort = $authenticationPort;
    }

    public function getSharedSecret()
    {
        return $this->sharedSecret;
    }

    public function setSharedSecret($sharedSecret)
    {
        $this->sharedSecret = $sharedSecret;
    }
}