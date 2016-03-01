<?php

namespace FIT\Bundle\ModuleLinuxBundle\Controller;

use FIT\NetopeerBundle\Controller\ModuleControllerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserAuthenticationController extends \FIT\NetopeerBundle\Controller\ModuleController {
     
        /**
	 * @Route("/sections/{key}/system/authentication", name="user_authentication")
	 * @Template
	 */
	public function userAuthenticationAction($key, $module = "system", $subsection = "authentication") {
            $res = $this->prepareDataForModuleAction("FITModuleLinuxBundle", $key, $module, $subsection);
		/* parent module did not prepares data, but returns redirect response,
		 * so we will follow this redirect
		 */
		if ($res instanceof RedirectResponse) {
			return $res;
			// data were prepared correctly
		} else {
			return $this->getTwigArr();
               }
        }
}
