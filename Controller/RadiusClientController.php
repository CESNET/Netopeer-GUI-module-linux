<?php

namespace FIT\Bundle\ModuleLinuxBundle\Controller;

use FIT\NetopeerBundle\Controller\ModuleControllerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RadiusClientController extends \FIT\NetopeerBundle\Controller\ModuleController {
    
        /**
	 * @Route("/sections/{key}/system/radius", name="radius_client")
	 * @Template
	 */
	public function radiusClientAction($key, $module = "system", $subsection = "radius") {
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