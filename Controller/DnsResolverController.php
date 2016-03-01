<?php

namespace FIT\Bundle\ModuleLinuxBundle\Controller;

use FIT\NetopeerBundle\Controller\ModuleControllerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DnsResolverController extends \FIT\NetopeerBundle\Controller\ModuleController {
    
        /**
	 * @Route("/sections/{key}/{module}/{subsection}/detail/", name="dns_resolver")
	 * @Template("FITModuleLinuxBundle:DnsResolver:dnsResolver.html.twig")
	 */
	public function dnsResolverAction($key, $module = "system", $subsection = "dns-resolver") {
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
