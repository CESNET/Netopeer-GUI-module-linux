<?php
/*
 * Copyright (C) 2012-2013 CESNET
 *
 * LICENSE TERMS
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in
 *    the documentation and/or other materials provided with the
 *    distribution.
 * 3. Neither the name of the Company nor the names of its contributors
 *    may be used to endorse or promote products derived from this
 *    software without specific prior written permission.
 *
 * ALTERNATIVELY, provided that this notice is retained in full, this
 * product may be distributed under the terms of the GNU General Public
 * License (GPL) version 2 or later, in which case the provisions
 * of the GPL apply INSTEAD OF those given above.
 *
 * This software is provided ``as is'', and any express or implied
 * warranties, including, but not limited to, the implied warranties of
 * merchantability and fitness for a particular purpose are disclaimed.
 * In no event shall the company or contributors be liable for any
 * direct, indirect, incidental, special, exemplary, or consequential
 * damages (including, but not limited to, procurement of substitute
 * goods or services; loss of use, data, or profits; or business
 * interruption) however caused and on any theory of liability, whether
 * in contract, strict liability, or tort (including negligence or
 * otherwise) arising in any way out of the use of this software, even
 * if advised of the possibility of such damage.
 *
 */
namespace FIT\Bundle\ModuleLinuxBundle\Controller;

use FIT\Bundle\ModuleLinuxBundle\LinuxSectionNames;
use FIT\NetopeerBundle\Controller\ModuleControllerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class ModuleController extends \FIT\NetopeerBundle\Controller\ModuleController implements ModuleControllerInterface
{
	/**
	 * @inheritdoc
	 * @Template("FITModuleLinuxBundle:Module:section.html.twig")
	 */
	public function moduleAction($key, $module = null, $subsection = null)
	{
		$res = $this->prepareDataForModuleAction("FITModuleLinuxBundle", $key, $module, $subsection);

		if ($res instanceof RedirectResponse) {
			return $res;
		} else {
			return $this->getTwigArr();
        }
	}

	/**
	 * @Route("/sections/{key}/system/", name="module_system", requirements={"key" = "\d+"})
	 * @Template("FITModuleLinuxBundle:Module:section.html.twig")
	 *
	 * @param int $key
	 * 
	 * @return array|null|\SimpleXMLIterator|RedirectResponse|Response
	 */
	public function moduleSystemAction($key)
	{
		$res = $this->prepareDataForModuleAction("FITModuleLinuxBundle", $key, LinuxSectionNames::MODULE_SYSTEM_NAME, null);

		if ($res instanceof RedirectResponse) {
			return $res;
		} else {
			return $this->getTwigArr();
		}
	}

	/**
	 * @Route("/sections/{key}/interfaces/", name="module_interfaces", requirements={"key" = "\d+"})
	 * @Template("FITModuleLinuxBundle:Module:section.html.twig")
	 *
	 * @param int $key
	 *
	 * @return array|null|\SimpleXMLIterator|RedirectResponse|Response
	 */
	public function moduleInterfacesAction($key)
	{
		$res = $this->prepareDataForModuleAction("FITModuleLinuxBundle", $key, LinuxSectionNames::MODULE_INTERFACES_NAME, null);

		if ($res instanceof RedirectResponse) {
			return $res;
		} else {
			return $this->getTwigArr();
		}
	}

	/**
	 * @Route("/sections/{key}/system/control/rpc", name="system_control")
	 * @Template("FITModuleLinuxBundle:SystemControl:systemControl.html.twig")
	 * 
	 * @param int $key
	 * 
	 * @return array|null|\SimpleXMLIterator|RedirectResponse|Response
	 */
	public function systemControlAction($key) {
		$res = $this->moduleAction($key, "system", "");
		return $res;
	}

	/**
	 * @Route("/sections/{key}/{module}/settings/detail", name="system_settings")
	 * @Template("FITModuleLinuxBundle:Settings:settings.html.twig")
	 *
	 * @param int $key
	 * @param string $module
	 *
	 * @return array|null|\SimpleXMLIterator|RedirectResponse|Response
	 */
	public function settingsAction($key, $module) {
		$res = $this->moduleAction($key, $module, "");
		return $res;
	}

	/**
	 * @Route("/ajax/item/remove/{key}/{module}/{subsection}", name="remove_item_form")
	 */
	public function removeServerFormAjaxAction($key, $module = "system", $subsection)
	{
		$res = $this->prepareDataForModuleAction("FITModuleLinuxBundle", $key, $module, $subsection);
		return $res;
	}

	/**
	 * @param int $key
	 * @return array
     */
	protected function getFeatures($key)
	{
		/**
		 * @var \FIT\NetopeerBundle\Models\Data $dataClass
		 */
		$dataClass = $this->get('DataModel');

		$session = $this->get('session');
		if (($systemFeatures = $session->get('features')) == null) {
			$info = $dataClass->handle("info", array('key' => $key), false);
		//	@file_put_contents($this->container->get('kernel')->getRootDir() . '/logs/tmp-files/featuresInfo.txt', print_r($info, true));
			$systemString = "ietf-system";
			$systemInfo = array_filter($info['capabilities'], function($var) use ($systemString) { return preg_match("/\b$systemString\b/i", $var); });
			$systemInfo = array_shift(array_values($systemInfo));

			$featureString = "features";
			$systemFeatures = explode('&', $systemInfo);
			$systemFeatures = array_filter($systemFeatures, function($var) use ($featureString) { return preg_match("/\b$featureString\b/i", $var); });
			$systemFeatures = array_shift(array_values($systemFeatures));
			$systemFeatures = explode('=', $systemFeatures)[1];
			$systemFeatures = explode(',', $systemFeatures);
			$session->set('features', $systemFeatures);
		}

		return $systemFeatures;
	}

	/**
	 * @param int $key
	 * @return array
	 */
	protected function getIanaCryptHashFeatures($key)
	{
		/**
		 * @var \FIT\NetopeerBundle\Models\Data $dataClass
		 */
		$dataClass = $this->get('DataModel');


			$info = $dataClass->handle("info", array('key' => $key), false);
			$systemString = "iana-crypt-hash";
			$systemInfo = array_filter($info['capabilities'], function($var) use ($systemString) { return preg_match("/\b$systemString\b/i", $var); });
			$systemInfo = array_shift(array_values($systemInfo));

			$featureString = "features";
			$systemFeatures = explode('&', $systemInfo);
			$systemFeatures = array_filter($systemFeatures, function($var) use ($featureString) { return preg_match("/\b$featureString\b/i", $var); });
			$systemFeatures = array_shift(array_values($systemFeatures));
			$systemFeatures = explode('=', $systemFeatures)[1];
			$systemFeatures = explode(',', $systemFeatures);



		return $systemFeatures;
	}

	/**
	 * @param int $key
	 * @param string $module
	 * @param string $subsection
	 * @return array|\Symfony\Component\HttpFoundation\Response
     */
	protected function getLinuxBundleTwigArr($key, $module, $subsection) {
		$request = $this->getRequest();
		$request->setMethod('GET');
		$this->getRequest()->headers->remove('X-Requested-With');
		$request->request = new ParameterBag();

		$this->prepareDataForModuleAction("FITModuleLinuxBundle", $key, $module, $subsection);
		$twigArr = $this->getTwigArr();

		return $twigArr;
	}

	/**
	 * @param int $key
	 * @param string $module
	 * @param string $subsection
	 * @return mixed
     */
	protected function getLinuxBundleConfigParams($key, $module, $subsection)
	{
		$request = $this->getRequest();
		$request->setMethod('GET');
		$this->getRequest()->headers->remove('X-Requested-With');
		$request->request = new ParameterBag();

		$this->prepareDataForModuleAction("FITModuleLinuxBundle", $key, $module, $subsection);
		$configParams = $this->getConfigParams();

		return $configParams;
	}

	/**
	 * @param Form $form
	 * @return mixed
     */
	protected function serializeForm($form)
	{
		$encoder = new XmlEncoder();
		$normalizers = array(new GetSetMethodNormalizer());
		$serializer = new Serializer($normalizers, array($encoder));
		$newXml = $serializer->serialize($form->getData(), 'xml');
		$newXmlObject = simplexml_load_string($newXml);
		return $newXmlObject;
	}

	/**
	 * @param int $key
	 * @param string $module
	 * @param string $subsection
	 * @param Form $form
     */
	protected function saveValidData($key, $module, $subsection, $form)
	{
		$dataClass = $this->get('DataModel');
		$configParams = $this->getLinuxBundleConfigParams($key, $module, $subsection);
		$newXmlObject = $this->serializeForm($form);

		// TEMP!!
		if ($module == "interfaces") {
			$newXmlDom = dom_import_simplexml($newXmlObject);
			$newXmlDom->getElementsByTagName("type")->item(0)->setAttribute("xmlns:ianaift", "urn:ietf:params:xml:ns:yang:iana-if-type");
			$newXmlDom->getElementsByTagName("ipv4")->item(0)->setAttribute("xmlns", "urn:ietf:params:xml:ns:yang:ietf-ip");
		}

		$originalXml = $dataClass->handle('getconfig', $configParams, false);
		$originalXmlObject = simplexml_load_string($originalXml);

		if ($this->createSubsectionNode($key, $subsection, $module, $originalXmlObject)) {
			$originalXml = $dataClass->handle('getconfig', $configParams, false);
			$originalXmlObject = simplexml_load_string($originalXml);
		}

		$this->createValidXml((strlen($subsection) != 0) ? $originalXmlObject->children() : $originalXmlObject, $newXmlObject);

		$xmlNameSpaces = $originalXmlObject->getNamespaces();
		if ( isset($xmlNameSpaces[""]) ) {
			$originalXmlObject->registerXPathNamespace("xmlns", $xmlNameSpaces[""]);
		}

		$this->addNodesToDelete(simplexml_load_string($originalXml), $originalXmlObject);

		$editConfigParams = array(
			'key' 	 => $key,
			'target' => "running",
			'source' => $configParams['source'],
			'config' => str_replace('<?xml version="1.0"?'.'>', '', $originalXmlObject->asXml())
		);

		$dataClass->handle('editconfig', $editConfigParams);
		$this->container->get('request')->getSession()->getFlashBag()->add('success', "Configuration was edited successfully");
	}


	/**
	 * @param \SimpleXMLElement $originalXmlObject
	 * @param \SimpleXMLElement $newXmlObject
     */
	private function createValidXml($originalXmlObject, $newXmlObject)
	{
		$originalXmlDom = dom_import_simplexml($originalXmlObject);
		while ($originalXmlDom->hasChildNodes()){
			$originalXmlDom->removeChild($originalXmlDom->childNodes->item(0));
		}

		$newXmlDom = dom_import_simplexml($newXmlObject);

		$this->appendNodesToDom($originalXmlDom, $newXmlDom);
	}

	/**
	 * @param \DOMNode $toDom
	 * @param \DOMNode $fromDom
     */
	private function appendNodesToDom($toDom, $fromDom) {
		foreach ($fromDom->childNodes as $child) {
			$isArray = false;
			if ($child->nodeName == "keys" || $child->nodeName == "iterator") {
				continue;
			}
			if ($child->hasChildNodes()) {
				foreach ($child->childNodes as $subChild) {
					if ($subChild->nodeName == "values") {
						$newDom = $toDom->appendChild(new \DOMElement($this->decamelizeString($child->nodeName)));
						if ($subChild->hasChildNodes()) {
							if ($subChild->childNodes->item(0)->nodeName == $child->nodeName) {
								$this->appendNodesToDom($newDom, $subChild->childNodes->item(0));
							}
							else {
								$this->appendNodesToDom($newDom, $subChild);
							}
						}
						$isArray = true;
					}
				}
			}
			if ($isArray) {
				continue;
			}
			if ($child->nodeValue != null && $child->nodeValue != "") {
				if ($this->isCamelCase($child->nodeName)) {
					$newDom = $toDom->appendChild(new \DOMElement($this->decamelizeString($child->nodeName)));
				} else {
					$newDom = $toDom->appendChild($toDom->ownerDocument->importNode($child, false));
				}

				if ($child->hasChildNodes()) {
					$this->appendNodesToDom($newDom, $child);
				}
			}
			else {
			//	$child->setAttribute("xmlns:xc", "urn:ietf:params:xml:ns:netconf:base:1.0");
			//	$child->setAttribute("xc:operation", "remove");
			}
		}
	}

	/**
	 * @param $simpleXmlOriginal
	 * @param $simpleXmlNew
	 * @param $deep
	 * @param $xpath
     */
	private function addNodesToDelete($simpleXmlOriginal, $simpleXmlNew, $deep = 0, $xpath = null)
	{
		if (!isset($xpath)) {
			$xpath='/xmlns:'.$simpleXmlOriginal->getName();
		}

		$position = array();

		foreach($simpleXmlOriginal->children() as $child) {

			$name = $child->getName();
			if (isset($position[$name])) {
				$position[$name]++;
			}
			else {
				$position[$name] = 1;
			}

			if ($deep < 1) {
				$childXpath = $xpath . '/xmlns:' . $name;
			} else {
				$childXpath = $xpath . '/' . $name;
			}

			if ($simpleXmlNew->xpath($childXpath) == null) {
				$parentElement = $simpleXmlNew->xpath($xpath);

				if (isset($parentElement) && is_array($parentElement) && array_key_exists(0, $parentElement)) {
					$parentElement = $parentElement[0];
				}

				$childElement = $parentElement->addChild($name, (string)$child);
				$childElement->addAttribute("xc:operation", "remove", "urn:ietf:params:xml:ns:netconf:base:1.0");

				$this->addNodesToRemoveUnderRemovedParent($child, $childElement);
				continue;

			} else if (($deep >= 0) && is_array($arrayElements = $simpleXmlNew->xpath($childXpath))) {
				$keyExistsInArray = false;
				foreach ($arrayElements as $arrayElement) {
					if ((string)$arrayElement == (string)$child) {
						$keyExistsInArray = true;
					}
					if (!(strlen((string)$arrayElement->children()) == 0) && ((string)$arrayElement->children() == (string)$child->children())) {
						$keyExistsInArray = true;
					}
				}

				if (!$keyExistsInArray) {
					$parentElement = $simpleXmlNew->xpath($xpath);
					if (isset($parentElement) && is_array($parentElement) && array_key_exists(0, $parentElement)) {
						$parentElement = $parentElement[0];
					}

					$childElement = $parentElement->addChild($name, (string)$child);
					$childElement->addAttribute("xc:operation", "remove", "urn:ietf:params:xml:ns:netconf:base:1.0");

					$this->addAllNodesToRemoveUnderRemovedParent($child, $childElement);
					continue;
				}
			}

			$deep++;
			$this->addNodesToDelete($child, $simpleXmlNew, $deep, $childXpath);
		}
	}

	/**
	 * @param $simpleXmlOriginal
	 * @param $parentElement
     */
	private function addAllNodesToRemoveUnderRemovedParent($simpleXmlOriginal, $parentElement) {
		foreach($simpleXmlOriginal->children() as $child) {
			$name = $child->getName();
			$childElement = $parentElement->addChild($name, (string)$child);
			$childElement->addAttribute("xc:operation", "remove", "urn:ietf:params:xml:ns:netconf:base:1.0");
			$this->addAllNodesToRemoveUnderRemovedParent($child, $childElement);
		}
	}

	/**
	 * @param string $text
	 * @return string
     */
	private function decamelizeString($text)
	{
		return  preg_replace(
			'/(^|[a-z])([A-Z])/e',
			'strtolower(strlen("\\1") ? "\\1-\\2" : "\\2")',
			$text
		);
	}

	/**
	 * @param string $text
	 * @return boolean
     */
	private function isCamelCase($text)
	{
		return preg_match('/(^|[a-z])([A-Z])/e', $text);
	}

	// DON'T REMOVE YET
	private function createSubsectionNode($key, $subsection, $module, $xml)
	{
		/*if ((strlen($module) != 0) && (strlen($subsection) != 0) && !$xml->xpath("/xmlns:".$module."/xmlns:".$subsection)) {
			$request = $this->getRequest();
			$xml = $this->getXmlForCurrentSection($key, $module, null);
			$tempRequest = $request->request;
			$request->request = new ParameterBag(array(
				'newNodeForm' => Array
				(
					'parent' => '--xmlns:'.$module,
					'label0_--'.$module.'--xmlns:label0' => $subsection
				)));
			@file_put_contents($this->container->get('kernel')->getRootDir() . '/logs/tmp-files/mainNodeAdd2-addRequest-.txt', print_r($request->request, true));

			$this->get('XMLoperations')->handleNewNodeForm($key, $this->getConfigParams());
			$request->request = $tempRequest;
			$xml = $this->getXmlForCurrentSection($key, $module, $subsection);
			if ( isset($xmlNameSpaces[""]) ) {
				$xml->registerXPathNamespace("xmlns", $xmlNameSpaces[""]);
				$xPathPrefix = "xmlns:";
			}
			return true;
		}*/
		return false;
	}

	// DON'T REMOVE YET
	private function getXmlForCurrentSection($key, $module, $subsection) {
		// TODO: Remove extra parts of code

		$dataClass = $this->get('DataModel');
		$this->bundleName = "FITModuleLinuxBundle";
		//$this->assign('module', $module);

		if ($this->getRequest()->getSession()->get('isLocking') !== true) {
			$this->addAjaxBlock("FITModuleLinuxBundle" . ':Module:section.html.twig', 'moduleJavascripts');
			$this->addAjaxBlock("FITModuleLinuxBundle" . ':Module:section.html.twig', 'moduleStylesheet');
			$this->addAjaxBlock("FITModuleLinuxBundle" . ':Module:section.html.twig', 'title');
			$this->addAjaxBlock("FITModuleLinuxBundle" . ':Module:section.html.twig', 'additionalTitle');
			$this->addAjaxBlock("FITModuleLinuxBundle" . ':Module:section.html.twig', 'state');
			$this->addAjaxBlock("FITModuleLinuxBundle" . ':Module:section.html.twig', 'leftColumn');
			$this->assign('historyHref', $this->getRequest()->getRequestUri());
		}

		$this->getRequest()->getSession()->remove('isLocking');
		$this->addAjaxBlock("FITModuleLinuxBundle".':Module:section.html.twig', 'alerts');
		$this->addAjaxBlock("FITModuleLinuxBundle".':Module:section.html.twig', 'topMenu');

		if ($dataClass->checkLoggedKeys() === 1) {
			$url = $this->get('request')->headers->get('referer');
			if (!strlen($url)) {
				$url = $this->generateUrl('connections');
			}

			$post_values = $this->getRequest()->get('configDataForm');
			@file_put_contents($this->container->get('kernel')->getRootDir() . '/logs/tmp-files/posted-values1.txt', array_values($post_values));
			return $this->redirect($url);
		}

		// now, we could set forms params with filter (even if we don't have module or subsection)
		// filter will be empty
		$filters = $dataClass->loadFilters($module, $subsection);
		$this->setSectionFormsParams($key, $filters['state'], $filters['config']);

		/** prepare necessary data for left column */
		$this->setActiveSectionKey($key);
		$this->setModuleOrSectionName($key, $module, $subsection);
		$this->assign('rpcMethods', $this->createRPCListFromModel($module, $subsection));
		$this->setModuleOutputStyles($key, $module);

		// if form has been send, we well process it
		// if ($this->getRequest()->getMethod() == 'POST') {
		//     return $this->processSectionForms($key, $module, $subsection);
		// }

		// we will prepare filter form in column
		$this->setSectionFilterForms($key);
		$this->generateTypeaheadPath($key, $module, $subsection);

		$activeNotifications = $this->getRequest()->getSession()->get('activeNotifications');
		if ( !isset($activeNotifications[$key]) || $activeNotifications[$key] !== true ) {
			$activeNotifications[$key] = true;
			$this->getRequest()->getSession()->set('activeNotifications', $activeNotifications);
			$this->addAjaxBlock("FITModuleLinuxBundle".':Module:section.html.twig', 'notifications');
		}

		// load model tree dump
		$modelTree = $dataClass->getModelTreeDump($module);
		if ($modelTree) {
			$this->assign('modelTreeDump', $modelTree);
		}

		$xml = $dataClass->handle('get', $this->getStateParams(), true);
		@file_put_contents($this->container->get('kernel')->getRootDir() . '/logs/tmp-files/a2.txt', $xml);
		$xml = simplexml_load_string($xml, 'SimpleXMLIterator');
		//@file_put_contents($this->container->get('kernel')->getRootDir() . '/logs/tmp-files/a3.txt', $xml->clock->asXml());

		return $xml;
	}
        

}
