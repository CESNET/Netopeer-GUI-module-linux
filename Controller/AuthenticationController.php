<?php

namespace FIT\Bundle\ModuleLinuxBundle\Controller;

use FIT\Bundle\ModuleLinuxBundle\Models\FormBuilders\AuthenticationType;
use FIT\Bundle\ModuleLinuxBundle\Models\FormBuilders\AuthenticationUserType;
use FIT\Bundle\ModuleLinuxBundle\Models\FormBuilders\RadiusServerType;
use FIT\Bundle\ModuleLinuxBundle\Models\FormBuilders\RadiusType;
use FIT\Bundle\ModuleLinuxBundle\Models\Forms\AuthenticationUser;
use FIT\Bundle\ModuleLinuxBundle\Models\Forms\Radius;
use FIT\Bundle\ModuleLinuxBundle\Models\Forms\RadiusServer;
use FIT\Bundle\ModuleLinuxBundle\Models\Forms\Authentication;
use FIT\Bundle\ModuleLinuxBundle\Models\Forms\AuthenticationUserKey;
use FIT\NetopeerBundle\Controller\ModuleControllerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationController extends \FIT\Bundle\ModuleLinuxBundle\Controller\ModuleController 
{
	/**
	 * @Route("/sections/{key}/{module}/authentication/{subsection}/detail", name="authentication")
	 * @Template("FITModuleLinuxBundle:Authentication:authentication.html.twig")
	 *
	 * @param int $key
	 * @param string $module
	 * @param string $subsection
	 *
	 * @return array|null|\SimpleXMLIterator|RedirectResponse|Response
	 */
	public function authenticationAction($key, $module = "system", $subsection = "authentication") 
	{
		if ($this->getRequest()->getMethod() == 'POST') {
			$res = false;
			if ($subsection == "authentication") {
				$res = $this->handleAuthenticationForm($key, $module, $subsection);
			}
			else if ($subsection == "radius") {
				$res = $this->handleRadiusForm($key, $module, $subsection);
			}
			if ($res !== true) {
				return $res;
			}
		}

		$twigArr = $this->getLinuxBundleTwigArr($key, $module, $subsection);

		if (array_key_exists('stateArr', $twigArr) && $subsection == "authentication") {
			$features = $this->getFeatures($key);
			$twigArr['featureAuthentication'] = (array_search('authentication', $features) !== false);
			$twigArr['featureLocalUsers'] = (array_search('local-users', $features) !== false);

			if (isset($twigArr['stateArr']->authentication)) {
				$form = $this->createForm(new AuthenticationType(), Authentication::createFromXml($twigArr['stateArr']->authentication), array('features' => $features));
			} else {
				$form = $this->createForm(new AuthenticationType(), new Authentication(), array('features' => $features));
			}
			$twigArr['formConfigAuthentication'] = $form->createView();
		}
		else if (array_key_exists('stateArr', $twigArr) && $subsection == "radius") {
			$features = $this->getFeatures($key);
			$twigArr['featureRadius'] = (array_search('radius', $features) !== false);

			if (isset($twigArr['stateArr']->radius)) {
				$form = $this->createForm(new RadiusType(), Radius::createFromXml($twigArr['stateArr']->radius));
			} else {
				$form = $this->createForm(new RadiusType(), new Radius());
			}
			$twigArr['formConfigRadius'] = $form->createView();
		}

		return $twigArr;
    }

	/**
	 * @Route("/ajax/authentication/user/modal", name="get_user_modal_form")
	 * @return Response
	 */
	public function getUserModalFormAjaxAction()
	{
		if (($userNumber = $this->getRequest()->get('userNumber')) != null) {
			$authentication = new Authentication();
			$authentication->getUser()[$userNumber] = new AuthenticationUser();
			$form = $this->createForm(new AuthenticationType(), $authentication);
		}
		else {
			$request = $this->getRequest();
			$authentication = new Authentication();
			$form = $this->createForm(new AuthenticationType(), $authentication);
			$form->handleRequest($request);
		}

		return $this->render('FITModuleLinuxBundle:Authentication:authenticationUserModal.html.twig', array( "formConfigAuthentication" => $form->createView()) );
	}

	/**
	 * @Route("/ajax/authentication/user/", name="get_user_form")
	 * @return Response
	 */
	public function getUserFormAjaxAction()
	{
		$request = $this->getRequest();
		$authentication = new Authentication();
		$form = $this->createForm(new AuthenticationType(), $authentication);
		$form->handleRequest($request);
		return $this->render('FITModuleLinuxBundle:Authentication:authenticationUserForm.html.twig', array( "formConfigAuthentication" => $form->createView()) );
	}

	/**
	 * @Route("/ajax/radius/server/modal", name="get_radius_server_modal_form")
	 * @return Response
	 */
	public function getServerRadiusModalFormAjaxAction()
	{
		if (($serverNumber = $this->getRequest()->get('serverNumber')) != null) {
			$radius = new Radius();
			$radius->getServer()[$serverNumber] = RadiusServer::createFromServerNumber($serverNumber);
			$form = $this->createForm(new RadiusType(), $radius);
		}
		else {
			$request = $this->getRequest();
			$radius = new Radius();
			$form = $this->createForm(new RadiusType(), $radius);
			$form->handleRequest($request);
		}

		return $this->render('FITModuleLinuxBundle:Authentication:radiusServerModal.html.twig', array( "formConfigRadius" => $form->createView()) );
	}

	/**
	 * @Route("/ajax/radius/server/", name="get_radius_server_form")
	 * @return Response
	 */
	public function getServerRadiusFormAjaxAction()
	{
		$request = $this->getRequest();
		$radius = new Radius();
		$form = $this->createForm(new RadiusType(), $radius);
		$form->handleRequest($request);
		return $this->render('FITModuleLinuxBundle:Authentication:radiusServerForm.html.twig', array( "formConfigRadius" => $form->createView()) );
	}

	/**
	 * @param int $key
	 * @param string $module
	 * @param string $subsection
	 * @return array|bool|Response
     */
	private function handleAuthenticationForm($key, $module, $subsection) {
		$request = $this->getRequest();
		$authentication = new Authentication();
		$form = $this->createForm(new AuthenticationType(), $authentication);
		$form->handleRequest($request);

		if (!$form->isValid()) {
			$twigArr = $this->getLinuxBundleTwigArr($key, $module, $subsection);

			$features = $this->getFeatures($key);
			$twigArr['featureAuthentication'] = (array_search('authentication', $features) !== false);
			$twigArr['featureLocalUsers'] = (array_search('local-users', $features) !== false);
			$twigArr['formConfigAuthentication'] = $form->createView();

			return $twigArr;
		}
		else {
			$this->saveValidData($key, $module, $subsection, $form);
			return true;
		}
	}

	/**
	 * @param int $key
	 * @param string $module
	 * @param string $subsection
	 * @return array|bool|Response
     */
	private function handleRadiusForm($key, $module, $subsection) {
		$request = $this->getRequest();
		$radius = new Radius();
		$form = $this->createForm(new RadiusType(), $radius);
		$form->handleRequest($request);

		if (!$form->isValid()) {
			$twigArr = $this->getLinuxBundleTwigArr($key, $module, $subsection);

			$features = $this->getFeatures($key);
			$twigArr['featureRadius'] = (array_search('radius', $features) !== false);
			$twigArr['formConfigRadius'] = $form->createView();

			return $twigArr;
		}
		else {
			$this->saveValidData($key, $module, $subsection, $form);
			return true;
		}
	}
}