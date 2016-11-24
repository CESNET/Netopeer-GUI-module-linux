<?php

namespace FIT\Bundle\ModuleLinuxBundle\Controller;

use FIT\Bundle\ModuleLinuxBundle\LinuxSectionNames;
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
use Symfony\Component\Form\FormError;
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
	public function authenticationAction($key, $module = LinuxSectionNames::MODULE_SYSTEM_NAME, $subsection = LinuxSectionNames::SUBSECTION_AUTHENTICATION_NAME) 
	{
		if ($this->getRequest()->getMethod() == 'POST') {
			$res = false;
			if ($subsection == LinuxSectionNames::SUBSECTION_AUTHENTICATION_NAME) {
				$res = $this->handleAuthenticationForm($key, $module, $subsection);
			}
			else if ($subsection == LinuxSectionNames::SUBSECTION_RADIUS_NAME) {
				$res = $this->handleRadiusForm($key, $module, $subsection);
			}
			if ($res !== true) {
				return $res;
			}
		}

		$twigArr = $this->getLinuxBundleTwigArr($key, $module, $subsection);

		if (array_key_exists('stateArr', $twigArr) && $subsection == LinuxSectionNames::SUBSECTION_AUTHENTICATION_NAME) {
			$features = $this->getFeatures($key);

			$twigArr['featureAuthentication'] = (array_search('authentication', $features) !== false);
			$twigArr['featureLocalUsers'] = (array_search('local-users', $features) !== false);

			if (isset($twigArr['stateArr']->{LinuxSectionNames::SUBSECTION_AUTHENTICATION_NAME})) {
				$form = $this->createForm(new AuthenticationType(), Authentication::createFromXml($twigArr['stateArr']->{LinuxSectionNames::SUBSECTION_AUTHENTICATION_NAME}), array('features' => $features));
			} else {
				$form = $this->createForm(new AuthenticationType(), new Authentication(), array('features' => $features));
			}
			$twigArr['formConfigAuthentication'] = $form->createView();
		}
		else if (array_key_exists('stateArr', $twigArr) && $subsection == LinuxSectionNames::SUBSECTION_RADIUS_NAME) {
			$features = $this->getFeatures($key);
			$twigArr['featureRadius'] = (array_search('radius', $features) !== false);

			if (isset($twigArr['stateArr']->{LinuxSectionNames::SUBSECTION_RADIUS_NAME})) {
				$form = $this->createForm(new RadiusType(), Radius::createFromXml($twigArr['stateArr']->{LinuxSectionNames::SUBSECTION_RADIUS_NAME}));
			} else {
				$form = $this->createForm(new RadiusType(), new Radius());
			}
			$twigArr['formConfigRadius'] = $form->createView();
		}

		return $twigArr;
    }

	/**
	 * @Route("/ajax/{key}/authentication/user/modal", name="get_user_modal_form")
	 * @param int $key
	 * @return Response
	 */
	public function getUserModalFormAjaxAction($key)
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
	 * @Route("/ajax/{key}/authentication/user/get", name="get_user_form")
	 * @param int $key
	 * @return Response
	 */
	public function getUserFormAjaxAction($key)
	{
		$request = $this->getRequest();
		$authentication = new Authentication();
		$form = $this->createForm(new AuthenticationType(), $authentication);
		$form->handleRequest($request);

		foreach ($authentication->getUser() as $user) {
			if ($user->getOldPassword() != "" || $user->getNewPassword() != "") {
				if ($user->getPassword() != "" && !$this->checkPassword($user->getPassword(), $user->getOldPassword())) {
					$form->addError(new FormError("wrong password for ".$user->getName()));
				}
			}
		}

		if ($form->isValid()) {
			return $this->render('FITModuleLinuxBundle:Authentication:authenticationUserForm.html.twig', array("formConfigAuthentication" => $form->createView()));
		}
		else {
			return $this->render('FITModuleLinuxBundle:Authentication:authenticationUserModal.html.twig', array( "formConfigAuthentication" => $form->createView()));
		}
	}

	/**
	 * @Route("/ajax/{key}/radius/server/modal", name="get_radius_server_modal_form")
	 * @param int $key
	 * @return Response
	 */
	public function getServerRadiusModalFormAjaxAction($key)
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
	 * @Route("/ajax/{key}/radius/server/", name="get_radius_server_form")
	 * @param int $key
	 * @return Response
	 */
	public function getServerRadiusFormAjaxAction($key)
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

		foreach ($authentication->getUser() as $user) {
			if ($user->getOldPassword() != "" || $user->getNewPassword() != "") {
				if ($user->getPassword() != "" && !$this->checkPassword($user->getPassword(), $user->getOldPassword())) {
					$form->addError(new FormError("wrong password for ".$user->getName()));
				}
			}
		}

		if (!$form->isValid()) {
			$twigArr = $this->getLinuxBundleTwigArr($key, $module, $subsection);

			$features = $this->getFeatures($key);
			$twigArr['featureAuthentication'] = (array_search('authentication', $features) !== false);
			$twigArr['featureLocalUsers'] = (array_search('local-users', $features) !== false);
			$twigArr['formConfigAuthentication'] = $form->createView();

			return $twigArr;
		}
		else {
			foreach ($authentication->getUser() as $user) {
				if ($user->getOldPassword() != "" || $user->getNewPassword() != "") {
					$hash = substr($user->getPassword(), 0, strrpos($user->getPassword(), "$"));
					$user->setPassword(crypt($user->getNewPassword(), $hash));
					$user->setNewPassword("");
					$user->setOldPassword("");
				}
			}

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

	/**
	 * @param string $hashedPassword
	 * @param string $password
	 * @return bool
	 */
	private function checkPassword($hashedPassword, $password) {
		$hash = substr($hashedPassword, 0, strrpos($hashedPassword, "$"));
		return (crypt($password, $hash) == $hashedPassword);
	}
}
