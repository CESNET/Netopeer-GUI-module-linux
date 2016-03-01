<?php

namespace FIT\Bundle\ModuleLinuxBundle\Controller;

use FIT\NetopeerBundle\Controller\ModuleControllerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SystemIdentificationController extends \FIT\NetopeerBundle\Controller\ModuleController {
            
        /**
	 * @Route("/sections/{key}/{module}/identification/detail/", name="system_identification")
	 * @Template("FITModuleLinuxBundle:SystemIdentification:systemIdentification.html.twig")
	 */
	public function systemIdentificationAction($key, $module = "system", $subsection = null) {
            $res = $this->prepareDataForModuleAction("FITModuleLinuxBundle", $key, $module, $subsection);
		/* parent module did not prepares data, but returns redirect response,
		 * so we will follow this redirect
		 */
            if ($res instanceof RedirectResponse) {
		return $res;
			// data were prepared correctly
            }
               
            $twigArr = $this->getTwigArr();
            return $twigArr; 
        }
}
