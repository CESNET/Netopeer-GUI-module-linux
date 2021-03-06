<?php

namespace FIT\Bundle\ModuleLinuxBundle\Models\Forms;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

class AuthenticationUser
{
    /**
     * @Assert\NotBlank(message="User name value must be filled in")
     */
    protected $name;

    protected $password;

    protected $oldPassword;

    protected $newPassword;

    protected $authorizedKey;
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function getPassword()
{
    return $this->password;
}

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;
    }

    public function getNewPassword()
    {
        return $this->newPassword;
    }

    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;
    }
    
    public function getAuthorizedKey()
    {
        return $this->authorizedKey;
    }

    public function setAuthorizedKey($authorizedKey)
    {
        $this->authorizedKey = $authorizedKey;
    }
    
    public function __construct()
    {
        $this->authorizedKey = new ArrayCollection();
    }
}