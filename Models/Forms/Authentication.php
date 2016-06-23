<?php

namespace FIT\Bundle\ModuleLinuxBundle\Models\Forms;


use Doctrine\Common\Collections\ArrayCollection;

class Authentication extends ConfigForm
{
    protected $userAuthenticationOrder;

    protected $user;

    public function getUserAuthenticationOrder()
    {
        return $this->userAuthenticationOrder;
    }

    public function setUserAuthenticationOrder($userAuthenticationOrder)
    {
        $this->userAuthenticationOrder = $userAuthenticationOrder;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function __construct()
    {
        $this->userAuthenticationOrder = new ArrayCollection();
        $this->user = new ArrayCollection();
    }

    public static function createFromXml($authenticationXml)
    {
        $authentication = new static();

        $index = 1;
        if (empty($authenticationXml->{'user-authentication-order'})) {
            $authentication->userAuthenticationOrder->add(new AuthenticationOrder());
        }
        else {
            foreach ($authenticationXml->{'user-authentication-order'} as $order) {
                $authenticationOrder = new AuthenticationOrder();
                $authenticationOrder->setUserAuthenticationOrder(DnsResolver::getNodeValue($order));
                $authentication->userAuthenticationOrder->add($authenticationOrder);
                $index++;
            }
        }

        $index = 1;
        foreach($authenticationXml->user as $user) {
            $authenticationUser = new AuthenticationUser();
            $authenticationUser->setName(Authentication::getNodeValue($user->name));
            $authenticationUser->setPassword(Authentication::getNodeValue($user->password));

            $keyIndex = 1;
            if (empty($user->{'authorized-key'})) {
                $authenticationUser->getAuthorizedKey()->add(new AuthenticationUserKey());
            }
            else {
                foreach ($user->{'authorized-key'} as $authorizedKey) {
                    $userAuthorizedKey = new AuthenticationUserKey();
                    $userAuthorizedKey->setName(Authentication::getNodeValue($authorizedKey->name));
                    $userAuthorizedKey->setAlgorithm(Authentication::getNodeValue($authorizedKey->algorithm));
                    $userAuthorizedKey->setKeyData(Authentication::getNodeValue($authorizedKey->{'key-data'}));

                    $authenticationUser->getAuthorizedKey()->add($userAuthorizedKey);
                    $keyIndex++;
                }
            }

            $authentication->user->add($authenticationUser);
            $index++;
        }

        return $authentication;
    }
}