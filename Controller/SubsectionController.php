<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FIT\Bundle\ModuleLinuxBundle\Controller;

use FIT\NetopeerBundle\Controller\ModuleControllerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SubsectionController extends \FIT\NetopeerBundle\Controller\BaseController
{
        /**
	 * @Route("/sections/{key}/system/ntp/detail", name="subsection_ntp_detail")
	 * @Template("FITModuleLinuxBundle:Subsection:ntpDetail.html.twig")
	 */
    
        public function ntpAction($key = 0)
	{
            
             return $key;
        }
}

