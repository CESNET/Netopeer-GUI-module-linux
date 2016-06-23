<?php

namespace FIT\Bundle\ModuleLinuxBundle\Models\Forms;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

class AuthenticationUserKey
{
    /**
     * @Assert\NotBlank(message="Authorized Key name value must be filled in")
     */
    protected $name;

    /**
     * @Assert\NotBlank(message="Authorized Key algorithm value must be filled in")
     */
    protected $algorithm;

    /**
     * @Assert\NotBlank(message="Authorized Key key-data value must be filled in")
     */
    protected $keyData;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getAlgorithm()
    {
        return $this->algorithm;
    }

    public function setAlgorithm($algorithm)
    {
        $this->algorithm = $algorithm;
    }

    public function getKeyData()
    {
        return $this->keyData;
    }

    public function setKeyData($keyData)
    {
        $this->keyData = $keyData;
    }
}