<?php

namespace FIT\Bundle\ModuleLinuxBundle\Controller;

use FIT\Bundle\ModuleLinuxBundle\Models\FormBuilders\InterfaceInformationType;
use FIT\Bundle\ModuleLinuxBundle\Models\FormBuilders\InterfacesType;
use FIT\Bundle\ModuleLinuxBundle\Models\Forms\InterfaceInformation;
use FIT\Bundle\ModuleLinuxBundle\Models\Forms\Interfaces;
use FIT\NetopeerBundle\Controller\ModuleControllerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FIT\Bundle\ModuleLinuxBundle\Models\Forms\Clock;
use FIT\Bundle\ModuleLinuxBundle\Models\Forms\Ntp;
use FIT\Bundle\ModuleLinuxBundle\Models\Forms\NtpServer;


class InterfacesController extends ModuleController
{
    /**
     * @Route("/sections/{key}/{module}/interfaceManagement/detail/", name="interface_management")
     * @Template("FITModuleLinuxBundle:InterfaceManagement:interfaceManagement.html.twig")
     *
     * @param int $key
     * @param string $module
     * @param string $subsection
     *
     * @return array|null|\SimpleXMLIterator|RedirectResponse|Response
     */
    public function systemTimeManagementAction($key, $module = "interfaces", $subsection = "")
    {
        if ($this->getRequest()->getMethod() == 'POST') {
            $res = $this->handleInterfacesForm($key, $module, $subsection);
            if ($res !== true) {
                return $res;
            }
        }

        $twigArr = $this->getLinuxBundleTwigArr($key, $module, $subsection);

        if (array_key_exists('stateArr', $twigArr)) {
            if (isset($twigArr['stateArr']) && $twigArr['stateArr'] != null) {
                $form = $this->createForm(new InterfacesType(), Interfaces::createFromXml($twigArr['stateArr']));
            } else {
                $form = $this->createForm(new InterfacesType(), new Interfaces());
            }
            $twigArr['formConfigInterfaces'] = $form->createView();
        }
        @file_put_contents($this->container->get('kernel')->getRootDir() . '/logs/tmp-files/interfaces.txt', print_r($form->getData(), true));

        return $twigArr;
    }

    /**
     * @Route("/ajax/interfaces/interface/modal", name="get_interface_modal_form")
     * @return Response
     */
    public function getInterfaceModalFormAjaxAction()
    {
        if (($interfaceNumber = $this->getRequest()->get('interfaceNumber')) != null) {
            $interfaces = new Interfaces();
            $interfaces->getInterface()[$interfaceNumber] = InterfaceInformation::createFromInterfaceNumber($interfaceNumber);
            $form = $this->createForm(new InterfacesType(), $interfaces);
        }
        else {
            $request = $this->getRequest();
            $interfaces = new Interfaces();
            $form = $this->createForm(new InterfacesType(), $interfaces);
            $form->handleRequest($request);
        }

/*
        $postValues = $this->getRequest()->get('configDataForm');
        if (isset($postValues)) {
            $configInterfaceForm = InterfaceInformation::createFromPost($postValues);
        }
        else {
            $interfaceNumber = $this->getRequest()->get('interfaceNumber');
            $configInterfaceForm = InterfaceInformation::createFromInterfaceNumber($interfaceNumber);
        }

        $form = $this->createForm(new InterfaceInformationType(), $configInterfaceForm);*/
        return $this->render('FITModuleLinuxBundle:InterfaceManagement:interfaceModal.html.twig', array( "formConfigInterfaces" => $form->createView()) );
    }

    /**
     * @Route("/ajax/interfaces/interface/get", name="get_interface_form")
     * @return Response
     */
    public function getInterfaceFormAjaxAction()
    {
        $request = $this->getRequest();
        $interfaces = new Interfaces();
        $form = $this->createForm(new InterfacesType(), $interfaces);
        $form->handleRequest($request);

        /*
        $postValues = $this->getRequest()->get('configDataForm');
        $configInterfaceForm = InterfaceInformation::createFromPost($postValues);
        $form = $this->createForm(new InterfaceInformationType(), $configInterfaceForm);*/
        return $this->render('FITModuleLinuxBundle:InterfaceManagement:interfaceForm.html.twig', array( "formConfigInterfaces" => $form->createView()) );
    }

    /**
     * @param int $key
     * @param string $module
     * @param string $subsection
     * @return array|bool|Response
     */
    private function handleInterfacesForm($key, $module, $subsection) {
        $request = $this->getRequest();
        $interfaces = new Interfaces();
        $form = $this->createForm(new InterfacesType(), $interfaces);
        $form->handleRequest($request);

        if (!$form->isValid()) {
            $twigArr = $this->getLinuxBundleTwigArr($key, $module, $subsection);
            $twigArr['formConfigInterfaces'] = $form->createView();

            return $twigArr;
        }
        else {
            $this->saveValidData($key, $module, $subsection, $form);
            return true;
        }
    }
}