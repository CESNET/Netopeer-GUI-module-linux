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

use FIT\NetopeerBundle\Controller\ModuleControllerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ModuleController extends \FIT\NetopeerBundle\Controller\ModuleController implements ModuleControllerInterface
{
	/**
	 * @inheritdoc
	 *
	 * @Template("FITModuleLinuxBundle:Module:section.html.twig")
	 */
	public function moduleAction($key, $module = null, $subsection = null)
	{
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
        
        
        /**
	 * @Route("/sections/{key}/system/identification/detail", name="system_identification")
	 * @Template
	 */
	public function systemIdentificationAction($key, $module = "system", $subsection = "hostname") {
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
        
        /**
	 * @Route("/sections/{key}/system/timemanagement/detail", name="system_time_management")
	 * @Template
	 */
	public function systemTimeManagementAction($key, $module = "system", $subsection = "clock") {
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
        
        
        /**
	 * @Route("/sections/{key}/system/dns-resolver/detail", name="dns_resolver")
	 * @Template
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
        
        
        /**
	 * @Route("/sections/{key}/system/radius/detail", name="radius_client")
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
        
        
        /**
	 * @Route("/sections/{key}/system/authentication/detail", name="user_authentication")
	 * @Template
	 */
	public function authenticationAction($key, $module = "system", $subsection = "authentication") {
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
        
        
        /**
	 * @Route("/sections/{key}/system/control/detail", name="system_control")
	 * @Template
	 */
	public function systemControlAction($key, $module = "system", $subsection = "clock") {
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
        
 /*-------------------------------------------------------------------------------------------------------------------------------------------------*/       
        /**
	 * @Route("/sections/{key}/system/timemanagement/ntp/detail", name="system_ntp_detail")
	 * @Template
	 */
	public function ntpDetailAction($key, $module = "system", $subsection = "ntp") {
            
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
        
        
        
        /**
	 * @Route("/sections/{key}/system/timemanagement/clock/detail", name="system_clock_detail")
	 * @Template
	 */
	public function clockDetailAction($key, $module = "system", $subsection = "clock") {
            
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
        
        
        /**
	 * @Route("/sections/{key}/system/hostname/detail", name="system_hostname_detail")
	 * @Template
	 */
	public function hostnameDetailAction($key, $module = "system", $subsection = "hostname") {
            
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
        
        /**
	 * @Route("/sections/{key}/system/authentication/authentication/detail", name="system_authentication_detail")
	 * @Template
	 */
	public function authenticationDetailAction($key, $module = "system", $subsection = "authentication") {
            
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
        
        /**
	 * @Route("/sections/{key}/system/dns-resolver/dns-resolver/detail", name="system_dns-resolver_detail")
	 * @Template
	 */
	public function dnsresolverDetailAction($key, $module = "system", $subsection = "dns-resolver") {
            
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
