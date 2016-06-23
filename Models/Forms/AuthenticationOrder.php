<?php

namespace FIT\Bundle\ModuleLinuxBundle\Models\Forms;

class AuthenticationOrder
{
    protected $userAuthenticationOrder;

    public function getUserAuthenticationOrder()
    {
        return $this->userAuthenticationOrder;
    }

    public function setUserAuthenticationOrder($userAuthenticationOrder)
    {
        $this->userAuthenticationOrder = $userAuthenticationOrder;
    }
}