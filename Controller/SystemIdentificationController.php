<?php

namespace FIT\Bundle\ModuleLinuxBundle\Controller;

use FIT\Bundle\ModuleLinuxBundle\Models\Forms\SystemIdentification;
use FIT\Bundle\ModuleLinuxBundle\Models\FormBuilders\SystemIdentificationType;
use FIT\NetopeerBundle\Controller\ModuleControllerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class SystemIdentificationController extends \FIT\Bundle\ModuleLinuxBundle\Controller\ModuleController {
            
    /**
	 * @Route("/sections/{key}/{module}/identification/detail/", name="system_identification")
	 * @Template("FITModuleLinuxBundle:SystemIdentification:systemIdentification.html.twig")
     *
     * @param int $key
     * @param string $module
     * @param string $subsection
     *
     * @return array|null|\SimpleXMLIterator|RedirectResponse|Response
	 */
	public function systemIdentificationAction($key, $module = "system", $subsection = null)
    {
        if ($this->getRequest()->getMethod() == 'POST') {
            $res = $this->handleSystemIdentificationForm($key, $module, $subsection);
            if ($res !== true) {
                return $res;
            }
        }

        $twigArr = $this->getLinuxBundleTwigArr($key, $module, $subsection);

        if (array_key_exists('stateArr', $twigArr)) {
            $form = $this->createForm(new SystemIdentificationType(), SystemIdentification::createFormFromXml($twigArr['stateArr']));
            $twigArr['formConfigIdentification'] = $form->createView();
        }

        return $twigArr;
    }

    /**
     * @param int $key
     * @param string $module
     * @param string $subsection
     * @return array|bool|Response
     */
    private function handleSystemIdentificationForm($key, $module, $subsection) {
        $request = $this->getRequest();
        $systemIdentification = new SystemIdentification();
        $form = $this->createForm(new SystemIdentificationType(), $systemIdentification);
        $form->handleRequest($request);

        if (!$form->isValid()) {
            $twigArr = $this->getLinuxBundleTwigArr($key, $module, $subsection);
            $twigArr['formConfigIdentification'] = $form->createView();

            return $twigArr;
        }
        else {
            $dataClass = $this->get('DataModel');
            $configParams = $this->getLinuxBundleConfigParams($key, $module, $subsection);
            $newXmlObject = $this->serializeForm($form);
            $newXmlDom = dom_import_simplexml($newXmlObject);

            $originalXml = $dataClass->handle('getconfig', $configParams, false);
            $originalXmlObject = simplexml_load_string($originalXml);
            $originalXmlDom = dom_import_simplexml($originalXmlObject);

            $this->addSystemInformationNodeToDom($originalXmlDom, $newXmlDom, "contact");
            $this->addSystemInformationNodeToDom($originalXmlDom, $newXmlDom, "hostname");
            $this->addSystemInformationNodeToDom($originalXmlDom, $newXmlDom, "location");

            $originalXmlObject = simplexml_import_dom($originalXmlDom)->asXml();

            $editConfigParams = array(
                'key' 	 => $key,
                'target' => "running",
                'source' => $configParams['source'],
                'config' => str_replace('<?xml version="1.0"?'.'>', '', $originalXmlObject)
            );

            $dataClass->handle('editconfig', $editConfigParams);

            $this->container->get('request')->getSession()->getFlashBag()->add('success', "Configuration was edited successfully");
            
            return true;
        }
    }

    /**
     * @param \DOMElement $originalXmlDom
     * @param \DOMElement $newXmlDom
     * @param string $inputName
     */
    private function addSystemInformationNodeToDom($originalXmlDom, $newXmlDom, $inputName)
    {
        if ($newXmlDom->getElementsByTagName($inputName)->item(0)->nodeValue != "") {
            if ($originalXmlDom->getElementsByTagName($inputName)->length >= 1) {
                $originalXmlDom->getElementsByTagName($inputName)->item(0)->nodeValue = $newXmlDom->getElementsByTagName($inputName)->item(0)->nodeValue;
            } else {
                $originalXmlDom->appendChild($originalXmlDom->ownerDocument->importNode($newXmlDom->getElementsByTagName($inputName)->item(0), false));
                $originalXmlDom->getElementsByTagName($inputName)->item(0)->nodeValue = $newXmlDom->getElementsByTagName($inputName)->item(0)->nodeValue;
            }
        } else {
            if ($originalXmlDom->getElementsByTagName($inputName)->length >= 1) {
                $originalXmlDom->getElementsByTagName($inputName)->item(0)->setAttribute("xmlns:xc", "urn:ietf:params:xml:ns:netconf:base:1.0");
                $originalXmlDom->getElementsByTagName($inputName)->item(0)->setAttribute("xc:operation", "remove");
            }
        }
    }
}
